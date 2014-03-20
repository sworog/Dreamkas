<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Guzzle\Plugin\Mock\MockPlugin;
use Lighthouse\CoreBundle\Document\File\FileUploader;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OrderControllerTest extends WebTestCase
{
    public function testPostOrderAction()
    {
        $storeId = $this->factory->store()->getStoreId();

        $product1 = $this->createProduct('1');
        $product2 = $this->createProduct('2');
        $product3 = $this->createProduct('3');

        $supplier = $this->factory->createSupplier();

        $this->factory->flush();

        $orderProducts = array(
            array(
                'product' => $product1,
                'quantity' => 3,
            ),
            array(
                'product' => $product2,
                'quantity' => 2,
            ),
            array(
                'product' => $product3,
                'quantity' => 5,
            ),
            array(
                'product' => $product1,
                'quantity' => 1,
            ),
        );

        $orderData = array(
            'supplier' => $supplier->id,
            'products' => $orderProducts,
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders',
            $orderData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($supplier->id, 'supplier.id', $response);
        Assert::assertJsonPathEquals(10001, 'number', $response);
        foreach ($orderProducts as $orderProduct) {
            Assert::assertJsonPathEquals($orderProduct['quantity'], 'products.*.quantity', $response);
            Assert::assertJsonPathEquals($orderProduct['product'], 'products.*.product.product.id', $response);
        }
    }

    public function testPostOrderEmptyProductsValidation()
    {
        $storeId = $this->factory->store()->getStoreId();
        $this->createProduct();
        $supplier = $this->factory->createSupplier();
        $this->factory->flush();

        $postData = array(
            'supplier' => $supplier->id,
            'products' => array(),
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders',
            $postData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Нужно добавить минимум один товар', 'errors.0', $response);
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationProvider
     */
    public function testPostOrderValidation($expectedCode, array $data, array $assertions = array())
    {
        $storeId = $this->factory->store()->getStoreId();
        $product = $this->createProduct();
        $supplier = $this->factory->createSupplier();
        $this->factory->flush();

        $postData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'product' => $product,
                    'quantity' => 1.11,
                )
            ),
        );

        if (array_key_exists('products', $data)) {
            $postData['products'][0] = $data['products'][0] + $postData['products'][0];
            unset($data['products']);
        }

        $postData = $data + $postData;

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders',
            $postData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                201,
                array('products' => array(array('quantity' => 7))),
            ),
            'empty quantity' => array(
                400,
                array('products' => array(array('quantity' => ''))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('products' => array(array('quantity' => -10))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('products' => array(array('quantity' => -1))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('products' => array(array('quantity' => 0))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                201,
                array('products' => array(array('quantity' => 2.5))),
            ),
            'float quantity with coma' => array(
                201,
                array('products' => array(array('quantity' => '2,5'))),
            ),
            'float quantity very float' => array(
                400,
                array('products' => array(array('quantity' => 2.5555))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float with coma' => array(
                400,
                array('products' => array(array('quantity' => '2,5555'))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('products' => array(array('quantity' => '2,5555'))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                    'children.products.children.0.children.quantity.errors.1'
                    =>
                    null
                )
            ),
            'not numeric quantity' => array(
                400,
                array('products' => array(array('quantity' => 'abc'))),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть числом'
                )
            ),
            /***********************************************************************************************
             * 'product'
             ***********************************************************************************************/
            'not valid product' => array(
                400,
                array('products' => array(array('product' => 'not_valid_product_id'))),
                array(
                    'children.products.children.0.children.product.errors.0'
                    =>
                    'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('products' => array(array('product' => ''))),
                array(
                    'children.products.children.0.children.product.errors.0'
                    =>
                    'Заполните это поле'
                ),
            ),
            /***********************************************************************************************
             * 'supplier'
             ***********************************************************************************************/
            'not valid supplier' => array(
                400,
                array('supplier' => 'notExists'),
                array(
                    'children.supplier.errors.0'
                    =>
                    'Такого поставщика не существует'
                ),
            ),
            'empty supplier' => array(
                400,
                array('supplier' => ''),
                array(
                    'children.supplier.errors.0'
                    =>
                    'Выберите поставщика'
                ),
            ),
        );
    }

    public function testGerOrdersAction()
    {
        $store1 = $this->factory->store()->getStore('1');
        $store2 = $this->factory->store()->getStore('2');

        $supplier1 = $this->factory->createSupplier('Перевоз1');
        $supplier2 = $this->factory->createSupplier('Перевоз2');
        $supplier3 = $this->factory->createSupplier('Перевоз3');

        $this->factory->createOrder($store1, $supplier1, '2014-02-14 04:05:06');
        $this->factory->createOrder($store1, $supplier2, '2014-02-13 04:05:06');
        $this->factory->createOrder($store1, $supplier3, '2014-02-13 14:05:06');
        $this->factory->createOrder($store2, $supplier1);
        $this->factory->createOrder($store2, $supplier3, date('r', time() + 120));

        $this->factory->flush();

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store1->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store1->id . '/orders'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $response);
        Assert::assertJsonPathEquals($supplier1->name, '0.supplier.name', $response, 1);
        Assert::assertJsonPathEquals($supplier2->name, '2.supplier.name', $response, 1);
        Assert::assertJsonPathEquals($supplier3->name, '1.supplier.name', $response, 1);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store2->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store2->id . '/orders'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals($supplier1->name, '1.supplier.name', $response, 1);
        Assert::assertJsonPathEquals($supplier3->name, '0.supplier.name', $response, 1);

        Assert::assertJsonPathEquals($supplier2->name, '*.supplier.name', $response, 0);
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider orderProductValidationProvider
     */
    public function testPostOrderProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $storeId = $this->factory->store()->getStoreId();
        $product = $this->createProduct();

        $postData = $data + array(
            'product' => $product,
            'quantity' => 1.11,
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders/products?validate=true',
            $postData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
    }
    
    public function orderProductValidationProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                200,
                array('quantity' => 7),
            ),
            'empty quantity' => array(
                400,
                array('quantity' => ''),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                200,
                array('quantity' => 2.5),
            ),
            'float quantity with coma' => array(
                200,
                array('quantity' => '2,5'),
            ),
            'float quantity very float' => array(
                400,
                array('quantity' => 2.5555),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float with coma' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'children.quantity.errors.0' => 'Значение не должно содержать больше 3 цифр после запятой',
                    'children.quantity.errors.1' => null
                )
            ),
            'not numeric quantity' => array(
                400,
                array('quantity' => 'abc'),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение должно быть числом'
                )
            ),
            /***********************************************************************************************
             * 'product'
             ***********************************************************************************************/
            'not valid product' => array(
                400,
                array('product' => 'not_valid_product_id'),
                array(
                    'children.product.errors.0'
                    =>
                        'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('product' => ''),
                array(
                    'children.product.errors.0'
                    =>
                        'Заполните это поле'
                ),
            ),
        );
    }

    public function testOrderProductVersion()
    {
        $productId = $this->createProduct(array('name' => 'original'));
        $supplier = $this->factory->createSupplier();
        $this->factory->flush();

        $postData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'product' => $productId,
                    'quantity' => 1.11,
                )
            ),
        );
        $storeId = $this->factory->store()->getStoreId();
        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonPathEquals('original', 'products.0.product.product.name', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);

        $orderId = $postResponse['id'];

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/orders/' . $orderId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('original', 'products.0.product.product.name', $getResponse);

        $this->updateProduct($productId, array('name' => 'modified'));

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/orders/' . $orderId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('original', 'products.0.product.product.name', $getResponse);
    }

    public function testOrderNumberCreation()
    {
        $storeId = $this->factory->store()->getStoreId();

        $product1 = $this->createProduct('1');
        $product2 = $this->createProduct('2');
        $product3 = $this->createProduct('3');

        $supplier = $this->factory->createSupplier();

        $this->factory->flush();

        $orderProducts = array(
            array(
                'product' => $product1,
                'quantity' => 3,
            ),
            array(
                'product' => $product2,
                'quantity' => 2,
            ),
            array(
                'product' => $product3,
                'quantity' => 5,
            ),
            array(
                'product' => $product1,
                'quantity' => 1,
            ),
        );

        $orderData = array(
            'supplier' => $supplier->id,
            'products' => $orderProducts,
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders',
            $orderData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(10001, 'number', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/orders',
            $orderData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonPathEquals(10002, 'number', $response);
    }

    public function testPutOrderAction()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');
        $store = $this->factory->store()->getStore();
        $supplier = $this->factory->createSupplier();
        $order = $this->factory->createOrder($store, $supplier);
        $orderProduct1 = $this->factory->createOrderProduct($order, $productId1, 10);
        $orderProduct2 = $this->factory->createOrderProduct($order, $productId2, 20);
        $orderProduct3 = $this->factory->createOrderProduct($order, $productId3, 30);
        $this->factory->flush();

        $this->assertEquals(10001, $order->number);

        $orderData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'id' => $orderProduct1->id,
                    'product' => $productId1,
                    'quantity' => 20,
                ),
                array(
                    'product' => $productId2,
                    'quantity' => 35,
                ),
            )
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/orders/' . $order->id,
            $orderData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('10001', 'number', $putResponse);
        Assert::assertJsonPathCount(2, 'products.*.id', $putResponse);
        Assert::assertJsonPathEquals($orderProduct1->id, 'products.0.id', $putResponse);
        Assert::assertJsonPathEquals($orderProduct2->id, 'products.1.id', $putResponse);
        Assert::assertNotJsonPathEquals($orderProduct3->id, 'products.*.id', $putResponse, 0);

        Assert::assertJsonPathEquals($productId1, 'products.0.product.product.id', $putResponse);
        Assert::assertJsonPathEquals(20, 'products.0.quantity', $putResponse);

        Assert::assertJsonPathEquals($productId2, 'products.1.product.product.id', $putResponse);
        Assert::assertJsonPathEquals(35, 'products.1.quantity', $putResponse);
    }

    public function testPutOrderActionInvalidStore()
    {
        $store1 = $this->factory->store()->getStore('1');
        $store2 = $this->factory->store()->getStore('2');
        $productId = $this->createProduct('1');
        $supplier = $this->factory->createSupplier();
        $order = $this->factory->createOrder($store1, $supplier);
        $orderProduct = $this->factory->createOrderProduct($order, $productId, 10);
        $this->factory->flush();

        $orderData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'id' => $orderProduct->id,
                    'product' => $productId,
                    'quantity' => 20,
                ),
            )
        );

        $departmentManager = $this->factory->store()->getDepartmentManager($store1->id);
        $this->factory->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $accessToken = $this->factory->oauth()->auth($departmentManager);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store2->id . '/orders/' . $order->id,
            $orderData
        );

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('Order object not found', 'message', $putResponse);
    }

    public function testGetOrderActionInvalidStore()
    {
        $store1 = $this->factory->store()->getStore('1');
        $store2 = $this->factory->store()->getStore('2');
        $productId = $this->createProduct('1');
        $supplier = $this->factory->createSupplier();
        $order = $this->factory->createOrder($store1, $supplier);
        $this->factory->createOrderProduct($order, $productId, 10);
        $this->factory->flush();

        $departmentManager = $this->factory->store()->getDepartmentManager($store1->id);
        $this->factory->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $accessToken = $this->factory->oauth()->auth($departmentManager);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store2->id . '/orders/' . $order->id
        );

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('Order object not found', 'message', $putResponse);
    }

    public function testDeleteOrderActionInvalidStore()
    {
        $store1 = $this->factory->store()->getStore('1');
        $store2 = $this->factory->store()->getStore('2');
        $productId = $this->createProduct('1');
        $supplier = $this->factory->createSupplier();
        $order = $this->factory->createOrder($store1, $supplier);
        $this->factory->createOrderProduct($order, $productId, 10);
        $this->factory->flush();

        $departmentManager = $this->factory->store()->getDepartmentManager($store1->id);
        $this->factory->store()->linkDepartmentManagers($departmentManager->id, $store2->id);

        $accessToken = $this->factory->oauth()->auth($departmentManager);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $store2->id . '/orders/' . $order->id
        );

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('Order object not found', 'message', $putResponse);
    }

    public function testDeleteAction()
    {
        $store = $this->factory->store()->getStore();
        $productId = $this->createProduct();
        $supplier = $this->factory->createSupplier();
        $order = $this->factory->createOrder($store, $supplier);
        $this->factory->createOrderProduct($order, $productId, 10);
        $this->factory->flush();

        $orderProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.order_product');
        $this->assertCount(1, $orderProductRepository->findAll());

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $store->id . '/orders/' . $order->id
        );

        $this->assertResponseCode(204);

        $this->assertEmpty($deleteResponse);

        $this->assertCount(0, $orderProductRepository->findAll());
    }

    public function testDownloadOrderAction()
    {
        $this->setUpStoreDepartmentManager();
        $supplier = $this->factory->createSupplier();
        $product1 = $this->createProduct(array('name' => 'Кефир1Назв', 'sku' => 'Кефир1Арт', 'barcode' => '1111111'));
        $product2 = $this->createProduct(array('name' => 'Кефир2Назв', 'sku' => 'Кефир2Арт', 'barcode' => '2222222'));
        $product3 = $this->createProduct(array('name' => 'Кефир3Назв', 'sku' => 'Кефир3Арт', 'barcode' => '3333333'));

        $this->factory->flush();

        $postData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'product' => $product1,
                    'quantity' => 3.11,
                ),
                array(
                    'product' => $product2,
                    'quantity' => 2,
                ),
                array(
                    'product' => $product3,
                    'quantity' => 7.77,
                ),
            )
        );

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders',
            $postData
        );

        $this->assertResponseCode(201);

        $orderId = $response['id'];
        $orderNumber = $response['number'];

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/upload.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/head.response.ok'));

        $mockGuzzle = function (ContainerInterface $container) use ($mockPlugin) {
            $client = $container->get('openstack.selectel');
            $client->addSubscriber($mockPlugin);
        };

        $mockFile = $this->getFixtureFilePath('OpenStack/auth.response.ok');
        $requestGetContentMock = function (ContainerInterface $container) use ($mockFile) {
            /* @var FileUploader $uploader */
            $uploader = $container->get('lighthouse.core.document.repository.file.uploader');
            $uploader->setFileResource(fopen($mockFile, 'rb'));
        };

        $this->client->addTweaker($mockGuzzle);
        $this->client->addTweaker($requestGetContentMock);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/orders/' . $orderId . '/download'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('order' . $orderNumber . '.xls', 'name', $response);
        Assert::assertJsonHasPath('url', $response);
    }

    public function testPutOrderActionChangeProduct()
    {
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');
        $store = $this->factory->store()->getStore();
        $supplier = $this->factory->createSupplier();
        $order = $this->factory->createOrder($store, $supplier);
        $orderProduct1 = $this->factory->createOrderProduct($order, $productId1, 10);
        $orderProduct2 = $this->factory->createOrderProduct($order, $productId2, 20);
        $this->factory->flush();

        $this->assertEquals(10001, $order->number);

        $orderData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'product' => $productId1,
                    'quantity' => 20,
                ),
                array(
                    'product' => $productId3,
                    'quantity' => 35,
                ),
            )
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/orders/' . $order->id,
            $orderData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('10001', 'number', $putResponse);
        Assert::assertJsonPathCount(2, 'products.*.id', $putResponse);
        Assert::assertJsonPathEquals($orderProduct1->id, 'products.0.id', $putResponse);
        Assert::assertJsonPathEquals($orderProduct2->id, 'products.1.id', $putResponse);
        Assert::assertNotJsonPathEquals($productId2, 'products.*.product.product.id', $putResponse);

        Assert::assertJsonPathEquals($productId1, 'products.0.product.product.id', $putResponse);
        Assert::assertJsonPathEquals(20, 'products.0.quantity', $putResponse);

        Assert::assertJsonPathEquals($productId3, 'products.1.product.product.id', $putResponse);
        Assert::assertJsonPathEquals(35, 'products.1.quantity', $putResponse);
    }
}
