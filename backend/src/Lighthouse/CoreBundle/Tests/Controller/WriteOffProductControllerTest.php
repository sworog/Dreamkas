<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\Factory\UserFactory;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Versionable\VersionRepository;

class WriteOffProductControllerTest extends WebTestCase
{
    /**
     * @dataProvider validationWriteOffProductProvider
     *
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWriteOffProductValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $writeOffId = $this->createWriteOff('345-783', null, $store->id);
        $productId = $this->createProduct();

        $writeOffProductData = $data + array(
            'product' => $productId,
            'price' => 7.99,
            'quantity' => 2,
            'cause' => 'Сгнил товар'
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products',
            'POST',
            $writeOffProductData
        );
        $postResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationWriteOffProductProvider()
    {
        return array(
            /***********************************************************************************************
             * 'quantity'
             ***********************************************************************************************/
            'valid quantity 7' => array(
                201,
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
                201,
                array('quantity' => 2.5),
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
            'float quantity with coma' => array(
                201,
                array('quantity' => '2,5'),
            ),
            'float quantity very float with coma' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                    'children.quantity.errors.1'
                    =>
                    null
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
             * 'price'
             ***********************************************************************************************/
            'valid price dot' => array(
                201,
                array('price' => 10.89),
            ),
            'valid price dot 79.99' => array(
                201,
                array('price' => 79.99),
            ),
            'valid price coma' => array(
                201,
                array('price' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('price' => ''),
                array(
                    'children.price.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('price' => '10,898'),
                array(
                    'children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('price' => '10.898'),
                array(
                    'children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid price very float with dot' => array(
                201,
                array('price' => '10.12')
            ),
            'not valid price not a number' => array(
                400,
                array('price' => 'not a number'),
                array(
                    'children.price.errors.0'
                    =>
                    'Значение должно быть числом',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('price' => 0),
            ),
            'not valid price negative' => array(
                400,
                array('price' => -10),
                array(
                    'children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('price' => 2000000001),
                array(
                    'children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('price' => '100000000'),
                array(
                    'children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'valid price too big 10 000 000' => array(
                201,
                array('price' => '10000000'),
            ),
            'not valid price too big 10 000 001' => array(
                400,
                array('price' => '10000001'),
                array(
                    'children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
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
            /***********************************************************************************************
             * 'cause'
             ***********************************************************************************************/
            'not valid empty cause' => array(
                400,
                array('cause' => ''),
                array(
                    'children.cause.errors.0'
                    =>
                    'Заполните это поле'
                ),
            ),
            'not valid cause long 1001' => array(
                400,
                array('cause' => str_repeat('z', 1001)),
                array(
                    'children.cause.errors.0'
                    =>
                    'Не более 1000 символов'
                ),
            ),
            'valid cause long 1000' => array(
                201,
                array('cause' => str_repeat("z", 1000)),
            ),
            'valid cause special symbols' => array(
                201,
                array('cause' => '!@#$%^&^&*QWEQWE}{}":<></.,][;.,`~\=0=-\\'),
            ),
        );
    }

    public function testPostActionWriteOffNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432', null, $store->id);

        $postData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $postRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products',
            'POST',
            $postData
        );
        $this->jsonRequest($postRequest, $accessToken);

        $this->assertResponseCode(201);

        $invalidRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidWriteOffId/products',
            'POST',
            $postData
        );
        $postResponse = $this->jsonRequest($invalidRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOff object not found', 'message', $postResponse);
    }

    public function testPutActionWriteOffProductNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432', null, $store->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 10, 5.59, "Порча", $store->id);

        $putData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(200);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/invalidId',
            'PUT',
            $putData
        );

        $putResponse = $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidWriteOffId/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $putResponse = $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOff object not found', 'message', $putResponse);

        $writeOffId2 = $this->createWriteOff('123-43432', null, $store->id);
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId, 10, 5.99, 'Порча', $store->id);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $putResponse = $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);

        $putRequest = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            'PUT',
            $putData
        );
        $putResponse = $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);
    }

    public function testDeleteActionWriteOffProductNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432', null, $store->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 10, 5.59, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/invalidId',
            'DELETE'
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidWriteOffId/products/' . $writeOffProductId,
            'DELETE'
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId,
            'DELETE'
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);
    }

    public function testGetWriteOffProductAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId
        );
        $getResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($writeOffProductId, 'id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId, 'writeOff.id', $getResponse);
        Assert::assertJsonPathEquals($productId, 'product.id', $getResponse);
    }

    public function testGetWriteOffProductActionNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $writeOffId1 = $this->createWriteOff('1', null, $store->id);
        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId1, $productId, 10, 5.59, 'Порча', $store->id);

        $writeOffId2 = $this->createWriteOff('2', null, $store->id);
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId, 10, 5.59, 'Порча', $store->id);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId1 . '/products/' . $writeOffProductId2
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId1
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidId/products/' . $writeOffProductId1
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/invalidId/products/' . $writeOffProductId2
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId1 . '/products/invalidId'
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest(
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products/invalidId'
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);
    }

    public function testProductAmountChangeOnWriteOf()
    {
        $storeId = $this->factory->store()->getStoreId();

        $productId1 = $this->createProduct(1);
        $productId2 = $this->createProduct(2);

        $this->assertStoreProductTotals($storeId, $productId1, 0);

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $storeId)
                ->createInvoiceProduct($productId1, 10, 4.99, $storeId)
                ->createInvoiceProduct($productId2, 20, 6.99, $storeId)
            ->flush();

        $this->assertStoreProductTotals($storeId, $productId1, 10, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        $writeOffId = $this->createWriteOff('431-678', null, $storeId);

        // create product 1 write off

        $postData = array(
            'product' => $productId1,
            'quantity' => 5,
            'price' => 3.49,
            'cause' => 'Порча',
        );

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffProductId1 = $postResponse['id'];

        $this->assertStoreProductTotals($storeId, $productId1, 5, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        // change product 1 write off quantity

        $putData1 = array(
            'product' => $productId1,
            'quantity' => 7,
            'price' => 4.49,
            'cause' => 'Порча',
        );

        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            'PUT',
            $putData1
        );
        $putResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($writeOffProductId1, 'id', $putResponse);

        $this->assertStoreProductTotals($storeId, $productId1, 3, 4.99);

        // write off product 2
        $putData2 = array(
            'product' => $productId2,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products',
            'POST',
            $putData2
        );
        $putResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $putResponse);

        $writeOffProductId2 = $putResponse['id'];

        $this->assertStoreProductTotals($storeId, $productId2, 16, 6.99);

        // Change product id

        $putData2 = array(
            'product' => $productId1,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            'PUT',
            $putData2
        );
        $putResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId1, 'product.id', $putResponse);

        $this->assertStoreProductTotals($storeId, $productId1, -1, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        // remove 2nd write off
        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            'DELETE'
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $this->assertStoreProductTotals($storeId, $productId1, 3, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);

        // remove 1st write off
        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            'DELETE'
        );
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $this->assertStoreProductTotals($storeId, $productId1, 10, 4.99);
        $this->assertStoreProductTotals($storeId, $productId2, 20, 6.99);
    }

    public function testGetWriteOffProductsAction()
    {
        $store = $this->factory()->store()->getStore();

        $product1 = $this->createProduct('1');
        $product2 = $this->createProduct('2');
        $product3 = $this->createProduct('3');

        $writeOffId1 = $this->createWriteOff('1', null, $store->id);
        $writeOffId2 = $this->createWriteOff('2', null, $store->id);

        $writeOffProduct1 = $this->createWriteOffProduct($writeOffId1, $product1, 10, 5.99, 'Порча', $store->id);
        $writeOffProduct2 = $this->createWriteOffProduct($writeOffId1, $product2, 10, 5.99, 'Порча', $store->id);
        $writeOffProduct3 = $this->createWriteOffProduct($writeOffId1, $product3, 10, 5.99, 'Порча', $store->id);

        $writeOffProduct4 = $this->createWriteOffProduct($writeOffId2, $product2, 10, 5.99, 'Порча', $store->id);
        $writeOffProduct5 = $this->createWriteOffProduct($writeOffId2, $product3, 10, 5.99, 'Порча', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest('/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId1 . '/products');
        $getResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct3, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($writeOffProduct4, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProduct5, '*.id', $getResponse);

        $request = new JsonRequest('/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId2 . '/products');
        $getResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct5, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($writeOffProduct1, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProduct2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProduct3, '*.id', $getResponse);
    }

    public function testGetWriteOffProductsActionNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_DEPARTMENT_MANAGER);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/123484923423/products'
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceProductsActionEmptyCollection()
    {
        $store = $this->factory()->store()->getStore();
        $writeOffId = $this->createWriteOff('1', null, $store->id);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $request = new JsonRequest('/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products');
        $response = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testProductDataDoesNotChangeInWriteOffAfterProductUpdate()
    {
        $store = $this->factory->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $writeoffProducts = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeoffProducts, 1);
        Assert::assertJsonPathEquals('кефир_1%', '*.product.sku', $writeoffProducts, 1);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $writeoffProducts = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeoffProducts, 1);
        Assert::assertJsonPathEquals('кефир_1%', '*.product.sku', $writeoffProducts, 1);

        $this->assertProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));
    }

    public function testTwoProductVersionsInWriteoff()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $writeOffProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $writeOffProductsResponse);
        Assert::assertJsonPathEquals($productId, '*.product.id', $writeOffProductsResponse, 2);
        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeOffProductsResponse, 1);
        Assert::assertJsonPathEquals('кефир_1%', '*.product.sku', $writeOffProductsResponse, 1);
        Assert::assertJsonPathEquals('Кефир 5%', '*.product.name', $writeOffProductsResponse, 1);
        Assert::assertJsonPathEquals('кефир_5%', '*.product.sku', $writeOffProductsResponse, 1);
    }

    public function testTwoProductVersionsCreated()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $writeOffId = $this->createWriteOff('1', null, $store->id);
        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->createWriteOffProduct($writeOffId, $productId, 10, 5.99, 'Бой', $store->id);

        /* @var VersionRepository $productVersionRepository*/
        $productVersionRepository = $this->getContainer()->get('lighthouse.core.document.repository.product_version');

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);

        $this->assertCount(2, $productVersions);
    }

    /**
     * @dataProvider departmentManagerCanNotAccessWriteOffFromAnotherStoreProvider
     * @param string $method
     * @param string $url
     * @param int $expectedCode
     * @param array|null $data
     */
    public function testDepartmentManagerCanNotAccessWriteOffFromAnotherStore(
        $method,
        $url,
        $expectedCode,
        array $data = null
    ) {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();

        $writeOffId1 = $this->createWriteOff('431-1111', null, $store1->id);
        $writeOffId2 = $this->createWriteOff('432-2222', null, $store2->id);

        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId1, $productId, 2, 20, 'Бой', $store1->id);
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId, 1, 10, 'Порча', $store2->id);

        $accessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $accessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        if (null !== $data) {
            $data += array(
                'product' => $productId,
                'price' => 7.99,
                'quantity' => 2,
                'cause' => 'Сгнил товар'
            );
        }

        $url1 = strtr(
            $url,
            array(
                '{store}' => $store1->id,
                '{writeOff}' => $writeOffId1,
                '{writeOffProduct}' => $writeOffProductId1
            )
        );

        $this->clientJsonRequest($accessToken2, $method, $url1, $data);
        $this->assertResponseCode(403);

        $this->clientJsonRequest($accessToken1, $method, $url1, $data);
        $this->assertResponseCode($expectedCode);

        $url2 = strtr(
            $url,
            array(
                '{store}' => $store2->id,
                '{writeOff}' => $writeOffId2,
                '{writeOffProduct}' => $writeOffProductId2
            )
        );

        $this->clientJsonRequest($accessToken1, $method, $url2, $data);
        $this->assertResponseCode(403);

        $this->clientJsonRequest($accessToken2, $method, $url2, $data);
        $this->assertResponseCode($expectedCode);
    }

    /**
     * @return array
     */
    public function departmentManagerCanNotAccessWriteOffFromAnotherStoreProvider()
    {
        return array(
            'GET' => array(
                'GET',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products',
                200
            ),
            'POST' => array(
                'POST',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products',
                201,
                array()
            ),
            'GET::{writeOffProduct}' => array(
                'GET',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products/{writeOffProduct}',
                200
            ),
            'PUT::{writeOffProduct}' => array(
                'PUT',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products/{writeOffProduct}',
                200,
                array()
            ),
            'DELETE::{writeOffProduct}' => array(
                'DELETE',
                '/api/1/stores/{store}/writeoffs/{writeOff}/products/{writeOffProduct}',
                204
            ),
        );
    }

    public function testGetWriteOffProductNotFoundFromAnotherStore()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('431', null, $store1->id);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 2, 19.25, 'Порча', $store1->id);

        $departmentManager = $this->factory()->store()->getDepartmentManager($store1->id);
        $this->factory()->store()->linkDepartmentManagers($departmentManager->id, $store2->id);
        $accessToken = $this->factory->oauth()->auth($departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store2->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store1->id . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId
        );

        $this->assertResponseCode(200);
    }

    public function testGetProductWriteOffProducts()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%', 'purchasePrice' => 35.24));
        $productId2 = $this->createProduct(array('name' => 'Кефир 5%', 'sku' => 'кефир_5%', 'purchasePrice' => 35.64));
        $productId3 = $this->createProduct(array('name' => 'Кефир 0%', 'sku' => 'кефир_0%', 'purchasePrice' => 42.15));

        $writeOffId1 = $this->createWriteOff('MU-866', '2013-10-18T09:39:47+0400', $store->id);

        $writeOffProductId11 = $this->createWriteOffProduct($writeOffId1, $productId1, 100, 36.70, 'Бой', $store->id);
        $writeOffProductId13 = $this->createWriteOffProduct($writeOffId1, $productId3, 20, 42.90, 'Бой', $store->id);

        $writeOffId2 = $this->createWriteOff('MU-864', '2013-10-18T12:22:00+0400', $store->id);
        $writeOffProductId21 = $this->createWriteOffProduct($writeOffId2, $productId1, 120, 37.20, 'Бой', $store->id);
        $writeOffProductId22 = $this->createWriteOffProduct($writeOffId2, $productId2, 200, 35.80, 'Бой', $store->id);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProductId13, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOffProductId22, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProductId11, '1.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProductId21, '0.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId1, '1.writeOff.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId2, '0.writeOff.id', $getResponse);
        Assert::assertNotJsonHasPath('*.store', $getResponse);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse);
    }

    public function testWriteOffProductTotalPriceWithFloatQuantity()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%', 'purchasePrice' => 35.24));
        $productId2 = $this->createProduct(array('name' => 'Кефир 5%', 'sku' => 'кефир_5%', 'purchasePrice' => 35.64));
        $productId3 = $this->createProduct(array('name' => 'Кефир 0%', 'sku' => 'кефир_0%', 'purchasePrice' => 42.15));

        $writeOffId1 = $this->createWriteOff('MU-866', '2013-10-18T09:39:47+0400', $store->id);

        $this->createWriteOffProduct($writeOffId1, $productId1, 99.99, 36.78, 'Порча', $store->id);
        $this->createWriteOffProduct($writeOffId1, $productId2, 0.4, 21.77, 'Порча', $store->id);
        $this->createWriteOffProduct($writeOffId1, $productId3, 7.77, 42.99, 'Порча', $store->id);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(36.78, "*.price", $getResponse);
        Assert::assertJsonPathEquals(99.99, "*.quantity", $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId2 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(8.71, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(0.4, "*.quantity", $getResponse);
        Assert::assertJsonPathEquals(21.77, "*.price", $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId3 . '/writeOffProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(334.03, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(42.99, "*.price", $getResponse);
        Assert::assertJsonPathEquals(7.77, "*.quantity", $getResponse);
    }

    public function testPutWithEmptyQuantity()
    {
        $storeId = $this->factory->store()->getStoreId();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('111', null, $storeId);
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId, 1, 9.99, 'Порча', $storeId);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId,
            array(
                'product' => $productId,
                'price' => 9.99,
                'quantity' => '',
                'cause' => 'Порча'
            )
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Заполните это поле', 'children.quantity.errors.0', $response);
    }

    public function testProductsActionCategoryIsNotExposed()
    {
        $storeId = $this->factory->store()->getStoreId();
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');
        $writeOffId = $this->createWriteOff('1', null, $storeId);
        $this->createWriteOffProduct($writeOffId, $productId1, 2, 5.99, 'Порча', $storeId);
        $this->createWriteOffProduct($writeOffId, $productId2, 1, 6.99, 'Порча', $storeId);
        $this->createWriteOffProduct($writeOffId, $productId3, 3, 2.59, 'Порча', $storeId);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products'
        );
        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('*.product.subCategory', $getResponse);
        Assert::assertJsonHasPath('*.writeOff', $getResponse);
        Assert::assertNotJsonHasPath('*.product.subCategory.category.group', $getResponse);
        Assert::assertJsonPathCount(0, '*.writeOff.products.*.id', $getResponse);
        Assert::assertNotJsonHasPath('*.product.subCategory.category', $getResponse);
    }
}
