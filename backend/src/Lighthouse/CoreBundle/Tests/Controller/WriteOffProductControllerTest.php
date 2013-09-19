<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Document\User\User;

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
        $writeOffId = $this->createWriteOff('345-783');
        $productId = $this->createProduct();

        $writeOffProductData = $data + array(
            'product' => $productId,
            'price' => 7.99,
            'quantity' => 2,
            'cause' => 'Сгнил товар'
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products', 'POST', $writeOffProductData);
        $postResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

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
                400,
                array('quantity' => 2.5),
                array(
                    'children.quantity.errors.0'
                    =>
                    'Значение должно быть целым числом'
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
                    'Цена не должна быть меньше или равна нулю',
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
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432');

        $postData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postRequest = new JsonRequest('/api/1/writeoffs/'. $writeOffId . '/products', 'POST', $postData);
        $this->jsonRequest($postRequest, $accessToken);

        $this->assertResponseCode(201);

        $invalidRequest = new JsonRequest('/api/1/writeoffs/invalidWriteOffId/products', 'POST', $postData);
        $postResponse = $this->jsonRequest($invalidRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOff object not found', 'message', $postResponse);
    }

    public function testPutActionWriteOffProductNotFound()
    {
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432');
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId);

        $putData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $putRequest = new JsonRequest(
            '/api/1/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(200);

        $putRequest = new JsonRequest(
            '/api/1/writeoffs/'. $writeOffId . '/products/invalidId',
            'PUT',
            $putData
        );

        $putResponse = $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);

        $putRequest = new JsonRequest(
            '/api/1/writeoffs/invalidWriteOffId/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $putResponse = $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOff object not found', 'message', $putResponse);

        $writeOffId2 = $this->createWriteOff('123-43432');
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId);

        $putRequest = new JsonRequest(
            '/api/1/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId,
            'PUT',
            $putData
        );
        $putResponse = $this->jsonRequest($putRequest, $accessToken);

        $this->assertResponseCode(404);
        // There is not message in debug=false mode
        Assert::assertJsonPathContains('WriteOffProduct object not found', 'message', $putResponse);

        $putRequest = new JsonRequest(
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
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
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432');
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId);

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $request = new JsonRequest('/api/1/writeoffs/'. $writeOffId . '/products/invalidId', 'DELETE');
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest('/api/1/writeoffs/invalidWriteOffId/products/' . $writeOffProductId, 'DELETE');
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest('/api/1/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId, 'DELETE');
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);
    }

    public function testGetWriteOffProductAction()
    {
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff();
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId);

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId);
        $getResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($writeOffProductId, 'id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId, 'writeOff.id', $getResponse);
        Assert::assertJsonPathEquals($productId, 'product.id', $getResponse);
    }

    public function testGetWriteOffProductActionNotFound()
    {
        $productId = $this->createProduct();

        $writeOffId1 = $this->createWriteOff('1');
        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId1, $productId);

        $writeOffId2 = $this->createWriteOff('2');
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId);

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId1 . '/products/' . $writeOffProductId2);
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId1);
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest('/api/1/writeoffs/invalidId/products/' . $writeOffProductId1);
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest('/api/1/writeoffs/invalidId/products/' . $writeOffProductId2);
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId1 . '/products/invalidId');
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);

        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId2 . '/products/invalidId');
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(404);
    }


    public function testProductAmountChangeOnWriteOf()
    {
        $productId1 = $this->createProduct(1);
        $productId2 = $this->createProduct(2);

        $this->assertProduct($productId1, array('amount' => null));

        $invoiceId = $this->createInvoice();

        $this->createInvoiceProduct($invoiceId, $productId1, 10, 4.99);
        $this->createInvoiceProduct($invoiceId, $productId2, 20, 6.99);

        $this->assertProduct($productId1, array('amount' => 10));
        $this->assertProduct($productId2, array('amount' => 20));

        $writeOffId = $this->createWriteOff('431-678');

        // create product 1 write off

        $postData = array(
            'product' => $productId1,
            'quantity' => 5,
            'price' => 3.49,
            'cause' => 'Порча',
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products', 'POST', $postData);
        $postResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffProductId1 = $postResponse['id'];

        $this->assertProductTotals($productId1, 5, 4.99);
        $this->assertProductTotals($productId2, 20, 6.99);

        // change product 1 write off quantity

        $putData1 = array(
            'product' => $productId1,
            'quantity' => 7,
            'price' => 4.49,
            'cause' => 'Порча',
        );

        $request = new JsonRequest(
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1,
            'PUT',
            $putData1
        );
        $putResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($writeOffProductId1, 'id', $putResponse);

        //Assert::assertJsonPathEquals(3, 'product.amount', $putResponse);
        $this->assertProduct($productId1, array('amount' => 3));

        // write off product 2
        $putData2 = array(
            'product' => $productId2,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products', 'POST', $putData2);
        $putResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $putResponse);

        $writeOffProductId2 = $putResponse['id'];

        $this->assertProductTotals($productId2, 16, 6.99);

        // Change product id

        $putData2 = array(
            'product' => $productId1,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $request = new JsonRequest(
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2,
            'PUT',
            $putData2
        );
        $putResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId1, 'product.id', $putResponse);

        $this->assertProductTotals($productId1, -1, 4.99);
        $this->assertProductTotals($productId2, 20, 6.99);

        // remove 2nd write off
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2, 'DELETE');
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $this->assertProductTotals($productId1, 3, 4.99);
        $this->assertProductTotals($productId2, 20, 6.99);

        // remove 1st write off
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1, 'DELETE');
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $this->assertProductTotals($productId1, 10, 4.99);
        $this->assertProductTotals($productId2, 20, 6.99);
    }

    public function testGetWriteOffProductsAction()
    {
        $product1 = $this->createProduct('1');
        $product2 = $this->createProduct('2');
        $product3 = $this->createProduct('3');

        $writeOffId1 = $this->createWriteOff('1');
        $writeOffId2 = $this->createWriteOff('2');

        $writeOffProduct1 = $this->createWriteOffProduct($writeOffId1, $product1);
        $writeOffProduct2 = $this->createWriteOffProduct($writeOffId1, $product2);
        $writeOffProduct3 = $this->createWriteOffProduct($writeOffId1, $product3);

        $writeOffProduct4 = $this->createWriteOffProduct($writeOffId2, $product2);
        $writeOffProduct5 = $this->createWriteOffProduct($writeOffId2, $product3);

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId1 . '/products');
        $getResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct3, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct4, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($writeOffProduct5, '*.id', $getResponse, false);

        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId2 . '/products');
        $getResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct5, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct1, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($writeOffProduct2, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($writeOffProduct3, '*.id', $getResponse, false);
    }

    public function testGetWriteOffProductsActionNotFound()
    {
        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs/123484923423/products'
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceProductsActionEmptyCollection()
    {
        $writeOffId = $this->createWriteOff();

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products');
        $response = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testProductDataDoesNotChangeInWriteOffAfterProductUpdate()
    {
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $writeOffId = $this->createWriteOff();
        $this->createWriteOffProduct($writeOffId, $productId);

        $accessToken = $this->authAsRole(User::ROLE_DEPARTMENT_MANAGER);

        $writeoffProducts = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeoffProducts, 1);
        Assert::assertJsonPathEquals('кефир_1%', '*.product.sku', $writeoffProducts, 1);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $writeoffProducts = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs/' . $writeOffId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $writeoffProducts, 1);
        Assert::assertJsonPathEquals('кефир_1%', '*.product.sku', $writeoffProducts, 1);

        $this->assertProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));
    }

    public function testTwoProductVersionsInWriteoff()
    {
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $writeOffId = $this->createWriteOff();
        $this->createWriteOffProduct($writeOffId, $productId);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->createWriteOffProduct($writeOffId, $productId);

        $accessToken = $this->authAsRole(User::ROLE_DEPARTMENT_MANAGER);
        $writeOffProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/writeoffs/' . $writeOffId . '/products'
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
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $writeOffId = $this->createWriteOff();
        $this->createWriteOffProduct($writeOffId, $productId);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->createWriteOffProduct($writeOffId, $productId);

        $productVersionRepository = $this->getContainer()->get('lighthouse.core.document.repository.product_version');

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);

        $this->assertCount(2, $productVersions);
    }
}
