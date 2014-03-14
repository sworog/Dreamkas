<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->setUpStoreDepartmentManager();
    }

    public function testPostOrderAction()
    {
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

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders',
            $orderData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($supplier->id, 'supplier.id', $response);
        Assert::assertJsonPathEquals(1, 'number', $response);
        foreach ($orderProducts as $orderProduct) {
            Assert::assertJsonPathEquals($orderProduct['quantity'], 'products.*.quantity', $response);
            Assert::assertJsonPathEquals($orderProduct['product'], 'products.*.product.product.id', $response);
        }
    }

    public function testPostOrderEmptyProductsValidation()
    {
        $this->createProduct();
        $supplier = $this->factory->createSupplier();
        $this->factory->flush();

        $postData = array(
            'supplier' => $supplier->id,
            'products' => array(),
        );

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders',
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

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders',
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

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider orderProductValidationProvider
     */
    public function testPostOrderProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $product = $this->createProduct();

        $postData = $data + array(
            'product' => $product,
            'quantity' => 1.11,
        );

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders/products?validate=true',
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
        $storeId = $this->factory->store()->getStore();
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
}
