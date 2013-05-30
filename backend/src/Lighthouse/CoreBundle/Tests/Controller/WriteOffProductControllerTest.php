<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

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
        $this->clearMongoDb();

        $writeOffId = $this->createWriteOff('345-783');
        $productId = $this->createProduct();

        $writeOffProductData = $data + array(
            'product' => $productId,
            'price' => 7.99,
            'quantity' => 2,
            'cause' => 'Сгнил товар'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs/' . $writeOffId . '/products.json',
            $writeOffProductData
        );

        Assert::assertResponseCode($expectedCode, $this->client);

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
        $this->clearMongoDb();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432');

        $postData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs/'. $writeOffId . '/products.json',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs/invalidWriteOffId/products.json',
            $postData
        );

        Assert::assertResponseCode(404, $this->client);
        Assert::assertJsonPathContains('WriteOff not found', 'message', $postResponse);
    }

    public function testPutActionWriteOffProductNotFound()
    {
        $this->clearMongoDb();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432');
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId);

        $putData = array(
            'product' => $productId,
            'price' => 6.99,
            'quantity' => 20,
            'cause' => 'Бой посуды',
        );

        $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId . '.json',
            $putData
        );

        Assert::assertResponseCode(200, $this->client);

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/'. $writeOffId . '/products/invalidId.json',
            $putData
        );

        Assert::assertResponseCode(404, $this->client);
        Assert::assertJsonPathContains('WriteOffProduct not found', 'message', $putResponse);

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/invalidWriteOffId/products/' . $writeOffProductId . '.json',
            $putData
        );

        Assert::assertResponseCode(404, $this->client);
        Assert::assertJsonPathContains('WriteOff not found', 'message', $putResponse);

        $writeOffId2 = $this->createWriteOff('123-43432');
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId);

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId . '.json',
            $putData
        );

        Assert::assertResponseCode(404, $this->client);
        Assert::assertJsonPathContains('WriteOffProduct not found', 'message', $putResponse);

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2 . '.json',
            $putData
        );

        Assert::assertResponseCode(404, $this->client);
        Assert::assertJsonPathContains('WriteOffProduct not found', 'message', $putResponse);
    }

    public function testDeleteActionWriteOffProductNotFound()
    {
        $this->clearMongoDb();
        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff('123-43432');
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId);

        $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/writeoffs/'. $writeOffId . '/products/invalidId.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/writeoffs/invalidWriteOffId/products/' . $writeOffProductId . '.json'
        );

        Assert::assertResponseCode(404, $this->client);


        $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/writeoffs/'. $writeOffId . '/products/' . $writeOffProductId . '.json'
        );

        Assert::assertResponseCode(204, $this->client);
    }

    public function testGetWriteOffProductAction()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();
        $writeOffId = $this->createWriteOff();
        $writeOffProductId = $this->createWriteOffProduct($writeOffId, $productId);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathEquals($writeOffProductId, 'id', $getResponse);
        Assert::assertJsonPathEquals($writeOffId, 'writeOff.id', $getResponse);
        Assert::assertJsonPathEquals($productId, 'product.id', $getResponse);
    }

    public function testGetWriteOffProductActionNotFound()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();

        $writeOffId1 = $this->createWriteOff('1');
        $writeOffProductId1 = $this->createWriteOffProduct($writeOffId1, $productId);

        $writeOffId2 = $this->createWriteOff('2');
        $writeOffProductId2 = $this->createWriteOffProduct($writeOffId2, $productId);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId1 . '/products/' . $writeOffProductId2 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId2 . '/products/' . $writeOffProductId1 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/invalidId/products/' . $writeOffProductId1 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/invalidId/products/' . $writeOffProductId2 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId1 . '/products/invalidId.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId2 . '/products/invalidId.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }


    public function testProductAmountChangeOnWriteOf()
    {
        $this->clearMongoDb();

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

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs/' . $writeOffId . '/products.json',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        $writeOffProductId1 = $postResponse['id'];

        Assert::assertJsonPathEquals(5, 'product.amount', $postResponse);

        $this->assertProduct($productId1, array('amount' => 5));
        $this->assertProduct($productId2, array('amount' => 20));

        // change product 1 write off quantity

        $putData1 = array(
            'product' => $productId1,
            'quantity' => 7,
            'price' => 4.49,
            'cause' => 'Порча',
        );

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1 . '.json',
            $putData1
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathEquals($writeOffProductId1, 'id', $putResponse);

        Assert::assertJsonPathEquals(3, 'product.amount', $putResponse);
        $this->assertProduct($productId1, array('amount' => 3));

        // write off product 2
        $putData2 = array(
            'product' => $productId2,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs/' . $writeOffId . '/products.json',
            $putData2
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $putResponse);
        Assert::assertJsonPathEquals(16, 'product.amount', $putResponse);

        $writeOffProductId2 = $putResponse['id'];

        $this->assertProduct($productId2, array('amount' => 16));

        // Change product id

        $putData2 = array(
            'product' => $productId1,
            'quantity' => 4,
            'price' => 20.99,
            'cause' => 'Бой посуды',
        );

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2 . '.json',
            $putData2
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathEquals($productId1, 'product.id', $putResponse);
        Assert::assertJsonPathEquals(-1, 'product.amount', $putResponse);

        $this->assertProduct($productId1, array('amount' => -1));
        $this->assertProduct($productId2, array('amount' => 20));

        // remove 2nd write off

        $deleteResponse = $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId2 . '.json'
        );

        Assert::assertResponseCode(204, $this->client);

        $this->assertProduct($productId1, array('amount' => 3));
        $this->assertProduct($productId2, array('amount' => 20));

        // remove 1st write off

        $deleteResponse = $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/writeoffs/' . $writeOffId . '/products/' . $writeOffProductId1 . '.json'
        );

        Assert::assertResponseCode(204, $this->client);

        $this->assertProduct($productId1, array('amount' => 10));
        $this->assertProduct($productId2, array('amount' => 20));
    }

    public function testGetWriteOffProductsAction()
    {
        $this->clearMongoDb();

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

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId1 . '/products.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct3, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct4, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($writeOffProduct5, '*.id', $getResponse, false);


        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId2 . '/products.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOffProduct4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct5, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($writeOffProduct1, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($writeOffProduct2, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($writeOffProduct3, '*.id', $getResponse, false);
    }

    public function testGetWriteOffProductsActionNotFound()
    {
        $this->clearMongoDb();

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/123484923423/products.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetInvoiceProductsActionEmptyCollection()
    {
        $this->clearMongoDb();

        $writeOffId = $this->createWriteOff();

        $response = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/writeoffs/' . $writeOffId . '/products.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }
}
