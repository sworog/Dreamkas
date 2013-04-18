<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class InvoiceProductControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider postActionProvider
     */
    public function testPostAction($quantity, $price, $totalPrice)
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => $quantity,
            'price'    => $price,
            'product'  => $productId,
        );

        $responseJson = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        Assert::assertResponseCode(201, $this->client->getResponse());
        Assert::assertJsonHasPath('id', $responseJson);

        Assert::assertJsonPathEquals($quantity, 'quantity', $responseJson);
        Assert::assertJsonPathEquals($price, 'price', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'totalPrice', $responseJson);
        Assert::assertJsonPathEquals($productId, 'product.id', $responseJson);
        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $responseJson);
    }

    /**
     * @return array
     */
    public function postActionProvider()
    {
        return array(
            array(11,   '72.12', '793.32'),
            array(1,    '72.12', '72.12'),
            array(3,    '72.12', '216.36'),
            array(9,    '72.12', '649.08'),
            array(1001, '72.12', '72192.12'),
            array(1009, '72.11', '72758.99'),
            array(1,    '72.00', '72.00'),
        );
    }

    public function testPostActionNotExistingInvoice()
    {
        $this->clearMongoDb();

        $invoiceId = 'dwedwdwdwwd';
        $productId = 'dewdewdwedw';

        $invoiceProductData = array(
            'quantity' => 10,
            'product'  => $productId,
        );

        $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testPostActionNotExistingField()
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => 10,
            'product'  => $productId,
            'dummy' => 'mummy',
            'foo' => 'bar',
        );

        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['errors'][0]));
        $this->assertContains(
            'Эта форма не должна содержать дополнительных полей: "dummy", "foo"',
            $response['errors'][0]
        );
    }

    public function testPostActionsNotExistingProduct()
    {
        $invoiceId = $this->createInvoice();

        $invoiceProductData = array(
            'quantity' => 10,
            'product' => 'dwdwdwd',
        );

        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['children']['product']['errors'][0]));
        $this->assertContains('Такого товара не существует', $response['children']['product']['errors'][0]);
    }

    public function testInvoiceTotalsAreUpdatedOnInvoiceProductPost()
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();

        $providerData = array(
            array(
                'product' => '_1',
                'quantity' => 10,
                'price' => 11.12,
                'itemsCount' => 1,
                'sumTotal' => 111.2
            ),
            array(
                'product' => '_2',
                'quantity' => 5,
                'price' => 12.76,
                'itemsCount' => 2,
                'sumTotal' => 175
            ),
            array(
                'product' => '_3',
                'quantity' => 1,
                'price' => 5.99,
                'itemsCount' => 3,
                'sumTotal' => 180.99
            ),
        );

        foreach ($providerData as $row) {
            $productId = $this->createProduct($row['product']);
            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price'    => $row['price'],
                'product'  => $productId,
            );

            $response = $this->clientJsonRequest(
                $this->client,
                'POST',
                '/api/1/invoices/' . $invoiceId . '/products.json',
                array('invoiceProduct' => $invoiceProductData)
            );

            Assert::assertResponseCode(201, $this->client->getResponse());

            Assert::assertJsonHasPath('id', $response);
            Assert::assertJsonPathEquals($row['quantity'], 'quantity', $response);
            Assert::assertJsonPathEquals($row['price'], 'price', $response);
            Assert::assertJsonPathEquals($row['itemsCount'], 'invoice.itemsCount', $response);
            Assert::assertJsonPathEquals($row['sumTotal'], 'invoice.sumTotal', $response);
        }
    }

    public function testProductAmountIsUpdatedOnInvoiceProductPost()
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $providerData = array(
            array(
                'product' => $productId,
                'quantity' => 10,
                'price' => 11.12,
                'productAmount' => 10,
            ),
            array(
                'product' => $productId,
                'quantity' => 5,
                'price' => 12.76,
                'productAmount' => 15,
            ),
            array(
                'product' => $productId,
                'quantity' => 1,
                'price' => 5.99,
                'productAmount' => 16,
            ),
        );

        foreach ($providerData as $row) {

            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price'    => $row['price'],
                'product'  => $row['product'],
            );

            $response = $this->clientJsonRequest(
                $this->client,
                'POST',
                '/api/1/invoices/' . $invoiceId . '/products.json',
                array('invoiceProduct' => $invoiceProductData)
            );

            Assert::assertResponseCode(201, $this->client->getResponse());

            Assert::assertJsonHasPath('id', $response);
            Assert::assertJsonPathEquals($row['quantity'], 'quantity', $response);
            Assert::assertJsonPathEquals($row['price'], 'price', $response);
            Assert::assertJsonPathEquals($row['productAmount'], 'product.amount', $response);
            Assert::assertJsonPathEquals($row['price'], 'product.lastPurchasePrice', $response);
        }
    }

    /**
     * @dataProvider validationProvider
     */
    public function testPostActionValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $invoiceProductData = $data + array(
            'quantity' => 10,
            'price'    => 17.68,
            'product'  => $productId,
        );

        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $response);
        }
    }

    public function validationProvider()
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
                array(
                    'children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                ),
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
        );
    }

    public function testGetInvoiceProductsAction()
    {
        $this->clearMongoDb();
        $this->client->restart();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $providerData = array(
            array(
                'product' => $productId,
                'quantity' => 10,
                'price' => 11.12,
                'productAmount' => 10,
            ),
            array(
                'product' => $productId,
                'quantity' => 5,
                'price' => 12.76,
                'productAmount' => 15,
            ),
            array(
                'product' => $productId,
                'quantity' => 1,
                'price' => 5.99,
                'productAmount' => 16,
            ),
        );

        foreach ($providerData as $i => $row) {

            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price'    => $row['price'],
                'product'  => $row['product'],
            );

            $response = $this->clientJsonRequest(
                $this->client,
                'POST',
                '/api/1/invoices/' . $invoiceId . '/products.json',
                array('invoiceProduct' => $invoiceProductData)
            );

            $this->assertEquals(201, $this->client->getResponse()->getStatusCode(), $this->client->getResponse());
            $providerData[$i]['id'] = $response['id'];
        }

        $response = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/' . $invoiceId . '/products.json'
        );

        $this->assertInternalType('array', $response);
        $this->assertCount(3, $response);
        foreach ($providerData as $i => $row) {
            $this->assertTrue(isset($response[$i]['id']));
            $this->assertEquals($row['id'], $response[$i]['id']);
            $this->assertTrue(isset($response[$i]['product']['id']));
            $this->assertEquals($row['product'], $response[$i]['product']['id']);
        }
    }

    public function testGetInvoiceProductsActionNotFound()
    {
        $this->clearMongoDb();
        $this->client->restart();

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/123484923423/products.json'
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testGetInvoiceProductsActionEmptyCollection()
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();

        $response = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/' . $invoiceId . '/products.json'
        );

        Assert::assertResponseCode(200, $this->client->getResponse());
        $this->assertInternalType('array', $response);
        $this->assertCount(0, $response);
    }

    public function testGetInvoiceProductNotFound()
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/' . $invoiceId . '/products/1.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/2/products/1.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetInvoiceProductNotFoundInvalidInvoiceId()
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => 10,
            'price'    => 17.68,
            'product'  => $productId,
        );

        $postJson = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceProductId = $postJson['id'];


        $getJson = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/' . $invoiceId . '/products/' . $invoiceProductId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathEquals($invoiceProductId, 'id', $getJson);

        $this->assertEquals($postJson, $getJson);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/324234234234/products/' . $invoiceProductId . '.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    /**
     * @dataProvider putInvoiceProvider
     */
    public function testPutInvoiceProductAction($quantity, $price, $totalPrice, $newQuantity, $newPrice, $newTotalPrice)
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => $quantity,
            'price'    => $price,
            'product'  => $productId,
        );

        $responseJson = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        Assert::assertResponseCode(201, $this->client->getResponse());
        Assert::assertJsonPathEquals($quantity, 'quantity', $responseJson);
        Assert::assertJsonPathEquals($price, 'price', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'product.id', $responseJson);
        Assert::assertJsonPathEquals($price, 'product.lastPurchasePrice', $responseJson);
        Assert::assertJsonPathEquals($quantity, 'product.amount', $responseJson);

        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $responseJson);
        Assert::assertJsonPathEquals(1, 'invoice.itemsCount', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'invoice.sumTotal', $responseJson);

        $invoiceProductId = $responseJson['id'];

        $modifiedInvoiceProductData = array(
            'quantity' => $newQuantity,
            'price' => $newPrice,
        ) + $invoiceProductData;

        $responseJson = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/invoices/' . $invoiceId . '/products/' . $invoiceProductId . '.json',
            array('invoiceProduct' => $modifiedInvoiceProductData)
        );

        Assert::assertResponseCode(200, $this->client->getResponse());
        Assert::assertJsonPathEquals($invoiceProductId, 'id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'product.id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'product.lastPurchasePrice', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'product.amount', $responseJson);

        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $responseJson);
        Assert::assertJsonPathEquals(1, 'invoice.itemsCount', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'invoice.sumTotal', $responseJson);

        $responseJson = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/invoices/' . $invoiceId . '/products/' . $invoiceProductId . '.json'
        );

        Assert::assertResponseCode(200, $this->client->getResponse());
        Assert::assertJsonPathEquals($invoiceProductId, 'id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'product.id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'product.lastPurchasePrice', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'product.amount', $responseJson);

        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $responseJson);
        Assert::assertJsonPathEquals(1, 'invoice.itemsCount', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'invoice.sumTotal', $responseJson);
    }

    /**
     * @return array
     */
    public function putInvoiceProvider()
    {
        return array(
            array(10, 5, 50, 20, 4, 80),
            array(6, 9.99, 59.94, 5, 9.99, 49.95)
        );
    }

    public function testPutInvoiceProductActionChangeProductId($quantity1 = 10, $price1 = 9.99, $quantity2 = 5, $price2 = 5.99)
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $product1Id = $this->createProduct('_1');
        $product2Id = $this->createProduct('_2');

        $this->assertProductTotals($product1Id, null, null);
        $this->assertProductTotals($product2Id, null, null);

        // POST invoice product
        $postData = array(
            'quantity' => $quantity1,
            'price'    => $price1,
            'product'  => $product1Id,
        );

        $postJson = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $postData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceProductId = $postJson['id'];
        Assert::assertJsonPathEquals($quantity1, 'product.amount', $postJson);
        Assert::assertJsonPathEquals($price1, 'product.lastPurchasePrice', $postJson);

        $this->assertProductTotals($product1Id, $quantity1, $price1);
        $this->assertProductTotals($product2Id, null, null);

        // PUT invoice product with another product id
        $putData = array(
            'quantity' => $quantity2,
            'price' => $price2,
            'product'  => $product2Id,
        );

        $putJson = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/invoices/' . $invoiceId . '/products/' . $invoiceProductId . '.json',
            array('invoiceProduct' => $putData)
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathEquals($quantity2, 'product.amount', $putJson);
        Assert::assertJsonPathEquals($price2, 'product.lastPurchasePrice', $putJson);

        $this->assertProductTotals($product1Id, 0, $price1);
        $this->assertProductTotals($product2Id, $quantity2, $price2);
    }

    /**
     * @param string $productId
     * @param int $amount
     * @param float $lastPurchasePrice
     */
    protected function assertProductTotals($productId, $amount, $lastPurchasePrice)
    {
        $productJson = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/products/' . $productId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);

        if (null === $amount) {
            Assert::assertNotJsonHasPath('amount', $productJson);
        } else {
            Assert::assertJsonPathEquals($amount, 'amount', $productJson);
        }

        if (null === $lastPurchasePrice) {
            Assert::assertNotJsonHasPath('lastPurchasePrice', $productJson);
        } else {
            Assert::assertJsonPathEquals($lastPurchasePrice, 'lastPurchasePrice', $productJson);
        }
    }

    /**
     * @return string
     */
    protected function createInvoice()
    {
        $invoiceData = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
        );

        $crawler = $this->client->request(
            'POST',
            '/api/1/invoices',
            array('invoice' => $invoiceData)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $invoiceId = $crawler->filterXPath("//invoice/id")->text();

        return $invoiceId;
    }

    /**
     * @param string $extra
     * @return string
     */
    protected function createProduct($extra = '')
    {
        $productData = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр' . $extra,
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР' . $extra,
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $crawler = $this->client->request(
            'POST',
            '/api/1/products',
            array('product' => $productData)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $productId = $crawler->filterXPath("//product/id")->text();

        return $productId;
    }
}
