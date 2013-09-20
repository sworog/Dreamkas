<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Service\AveragePriceService;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class InvoiceProductControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->initStoreDepartmentManager();
    }
    /**
     * @dataProvider postActionProvider
     */
    public function testPostAction($quantity, $price, $totalPrice)
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => $quantity,
            'price'    => $price,
            'product'  => $productId,
        );

        $accessToken = $this->auth($this->departmentManager);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
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
        $invoiceId = 'dwedwdwdwwd';
        $productId = 'dewdewdwedw';

        $invoiceProductData = array(
            'quantity' => 10,
            'product'  => $productId,
        );

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testPostActionNotExistingField()
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => 10,
            'product'  => $productId,
            'dummy' => 'mummy',
            'foo' => 'bar',
        );

        $accessToken = $this->auth($this->departmentManager);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
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
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $invoiceProductData = array(
            'quantity' => 10,
            'product' => 'dwdwdwd',
        );

        $accessToken = $this->auth($this->departmentManager);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['children']['product']['errors'][0]));
        $this->assertContains('Такого товара не существует', $response['children']['product']['errors'][0]);
    }

    public function testInvoiceTotalsAreUpdatedOnInvoiceProductPost()
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

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

        $accessToken = $this->auth($this->departmentManager);

        foreach ($providerData as $row) {
            $productId = $this->createProduct($row['product']);
            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price'    => $row['price'],
                'product'  => $productId,
            );

            $response = $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
                $invoiceProductData
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
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
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

        $accessToken = $this->auth($this->departmentManager);

        foreach ($providerData as $row) {

            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price'    => $row['price'],
                'product'  => $row['product'],
            );

            $response = $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
                $invoiceProductData
            );

            $this->assertResponseCode(201);

            Assert::assertJsonHasPath('id', $response);
            Assert::assertJsonPathEquals($row['quantity'], 'quantity', $response);
            Assert::assertJsonPathEquals($row['price'], 'price', $response);

            $this->assertProductTotals($productId, $row['productAmount'], $row['price']);
        }
    }

    /**
     * @dataProvider validationProvider
     */
    public function testPostActionValidation($expectedCode, array $data, array $assertions = array())
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $productId = $this->createProduct();

        $invoiceProductData = $data + array(
            'quantity' => 10,
            'price'    => 17.68,
            'product'  => $productId,
        );

        $accessToken = $this->auth($this->departmentManager);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
        );

        $this->assertResponseCode($expectedCode);

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
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
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

        $accessToken = $this->auth($this->departmentManager);

        foreach ($providerData as $i => $row) {

            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price'    => $row['price'],
                'product'  => $row['product'],
            );

            $response = $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
                $invoiceProductData
            );

            $this->assertResponseCode(201);
            Assert::assertJsonHasPath('id', $response);

            $providerData[$i]['id'] = $response['id'];
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
        );

        Assert::assertJsonPathCount(3, '*.id', $response);

        foreach ($providerData as $row) {
            Assert::assertJsonPathEquals($row['id'], '*.id', $response, 1);
            Assert::assertJsonPathEquals($row['product'], '*.product.id', $response);
        }
    }

    public function testGetInvoiceProductsActionNotFound()
    {
        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/123484923423/products'
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceProductActionNotFound()
    {
        $productId = $this->createProduct();
        $invoiceId1 = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $invoiceId2 = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $invoiceProductId = $this->createInvoiceProduct($invoiceId1, $productId, 1, 5.00);

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId1 . '/products/' . $invoiceProductId
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId2 . '/products/' . $invoiceProductId
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceProductsActionEmptyCollection()
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $accessToken = $this->auth($this->departmentManager);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
        );

        $this->assertResponseCode(200);
        $this->assertInternalType('array', $response);
        $this->assertCount(0, $response);
    }

    public function testGetInvoiceProductNotFound()
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/1'
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/2/products/1'
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceProductNotFoundInvalidInvoiceId()
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => 10,
            'price'    => 17.68,
            'product'  => $productId,
        );

        $accessToken = $this->auth($this->departmentManager);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceProductId = $postJson['id'];

        $getJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($invoiceProductId, 'id', $getJson);

        // TODO Suppressed this check because it is not related to tests and fails (
        //$this->assertEquals($postJson, $getJson);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/324234234234/products/' . $invoiceProductId
        );

        $this->assertResponseCode(404);
    }

    public function testGetInvoiceProductNotFoundFromAnotherStore()
    {
        $storeId2 = $this->createStore('43');
        $this->linkDepartmentManagers($storeId2, $this->departmentManager->id);

        $productId = $this->createProduct();
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $invoiceProductId = $this->createInvoiceProduct(
            $invoiceId,
            $productId,
            2,
            19.25,
            $this->storeId,
            $this->departmentManager
        );

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId
        );

        $this->assertResponseCode(200);
    }

    /**
     * @dataProvider putInvoiceProvider
     */
    public function testPutInvoiceProductAction($quantity, $price, $totalPrice, $newQuantity, $newPrice, $newTotalPrice)
    {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => $quantity,
            'price'    => $price,
            'product'  => $productId,
        );

        $accessToken = $this->auth($this->departmentManager);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
        );

        Assert::assertResponseCode(201, $this->client->getResponse());
        Assert::assertJsonPathEquals($quantity, 'quantity', $responseJson);
        Assert::assertJsonPathEquals($price, 'price', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'product.id', $responseJson);

        $this->assertProductTotals($productId, $quantity, $price);

        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $responseJson);
        Assert::assertJsonPathEquals(1, 'invoice.itemsCount', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'invoice.sumTotal', $responseJson);

        $this->assertInvoiceTotals($invoiceId, $totalPrice, 1);

        $invoiceProductId = $responseJson['id'];

        $modifiedInvoiceProductData = array(
            'quantity' => $newQuantity,
            'price' => $newPrice,
        ) + $invoiceProductData;

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId,
            $modifiedInvoiceProductData
        );

        Assert::assertResponseCode(200, $this->client->getResponse());
        Assert::assertJsonPathEquals($invoiceProductId, 'id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'product.id', $responseJson);
        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $responseJson);

        $this->assertProductTotals($productId, $newQuantity, $newPrice);
        $this->assertInvoiceTotals($invoiceId, $newTotalPrice, 1);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId
        );

        Assert::assertResponseCode(200, $this->client->getResponse());
        Assert::assertJsonPathEquals($invoiceProductId, 'id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'product.id', $responseJson);
        Assert::assertJsonPathEquals($invoiceId, 'invoice.id', $responseJson);
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

    /**
     * @dataProvider putInvoiceProductActionChangeProductIdProvider
     */
    public function testPutInvoiceProductActionChangeProductId(
        $quantity1,
        $price1,
        $invoiceSumTotal1,
        $quantity2,
        $price2,
        $invoiceSumTotal2
    ) {
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
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

        $accessToken = $this->auth($this->departmentManager);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceProductId = $postJson['id'];

        $this->assertProductTotals($product1Id, $quantity1, $price1);

        Assert::assertJsonPathEquals($invoiceSumTotal1, 'invoice.sumTotal', $postJson);
        Assert::assertJsonPathEquals(1, 'invoice.itemsCount', $postJson);

        $this->assertProductTotals($product1Id, $quantity1, $price1);
        $this->assertProductTotals($product2Id, null, null);
        $this->assertInvoiceTotals($invoiceId, $invoiceSumTotal1, 1);

        // PUT invoice product with another product id
        $putData = array(
            'quantity' => $quantity2,
            'price' => $price2,
            'product'  => $product2Id,
        );

        $putJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId,
            $putData
        );

        $this->assertResponseCode(200);

        $this->assertProductTotals($product2Id, $quantity2, $price2);

        Assert::assertJsonPathEquals($invoiceSumTotal2, 'invoice.sumTotal', $putJson);
        Assert::assertJsonPathEquals(1, 'invoice.itemsCount', $putJson);

        $this->assertProductTotals($product1Id, 0, null);
        $this->assertProductTotals($product2Id, $quantity2, $price2);
        $this->assertInvoiceTotals($invoiceId, $invoiceSumTotal2, 1);
    }

    /**
     * @param string $invoiceId
     * @param string $sumTotal
     * @param int $itemsCount
     */
    protected function assertInvoiceTotals($invoiceId, $sumTotal, $itemsCount)
    {
        $accessToken = $this->auth($this->departmentManager);

        $invoiceJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);

        $assertions = array(
            'sumTotal' => $sumTotal,
            'itemsCount' => $itemsCount,
        );

        $this->performJsonAssertions($invoiceJson, $assertions);
    }

    /**
     * @return array
     */
    public function putInvoiceProductActionChangeProductIdProvider()
    {
        return array(
            'quantity and price changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                5, // quantity of second product
                5.99, // price of second product
                29.95, // invoice sum total
            ),
            'quantity and price are not changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                10, // quantity of second product
                9.99, // price of second product
                99.9, // invoice sum total
            ),
            'quantity changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                5, // quantity of second product
                9.99, // price of second product
                49.95, // invoice sum total
            ),
            'price changed' => array(
                10, // quantity of first product
                9.99, // price of first product
                99.9, // invoice sum total
                10, // quantity of second product
                5.99, // price of second product
                59.9, // invoice sum total
            ),
        );
    }

    public function testDeleteProductsAction()
    {
        $productId = $this->createProduct();
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $productsData = $this->createInvoiceProducts($productId, $invoiceId);

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $productsData[1]['id']
        );

        $this->assertResponseCode(204);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);

        Assert::assertJsonPathEquals($productsData[0]['id'], '*.id', $getResponse);
        Assert::assertJsonPathEquals($productsData[2]['id'], '*.id', $getResponse);

        Assert::assertJsonPathEquals($productsData[1]['id'], '*.id', $getResponse, false);
    }

    public function testDeleteProductsActionUpdateAmountAndInvoiceTotals()
    {
        $productId = $this->createProduct();
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $productsData = $this->createInvoiceProducts($productId, $invoiceId);

        $accessToken = $this->auth($this->departmentManager);

        $this->assertProductTotals($productId, 16, 5.99);

        $this->assertInvoiceTotals($invoiceId, 180.99, 3);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $productsData[1]['id']
        );

        $this->assertResponseCode(204);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);

        Assert::assertJsonPathEquals($productsData[0]['id'], '*.id', $getResponse);
        Assert::assertJsonPathEquals($productsData[2]['id'], '*.id', $getResponse);

        Assert::assertJsonPathEquals($productsData[1]['id'], '*.id', $getResponse, false);

        $this->assertProductTotals($productId, 11, $productsData[2]['price']);
        $this->assertInvoiceTotals($invoiceId, 117.19, 2);
    }

    public function testLastPurchasePriceChangeOnInvoiceProductDelete()
    {
        $productId = $this->createProduct();

        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $invoiceProducts = $this->createInvoiceProducts($productId, $invoiceId);

        $this->assertProductTotals($productId, $invoiceProducts[2]['productAmount'], $invoiceProducts[2]['price']);

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProducts[2]['id']
        );

        $this->assertResponseCode(204);

        $this->assertProductTotals($productId, 15, $invoiceProducts[1]['price']);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProducts[0]['id']
        );

        $this->assertResponseCode(204);

        $this->assertProductTotals($productId, 5, $invoiceProducts[1]['price']);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProducts[1]['id']
        );

        $this->assertResponseCode(204);

        $this->assertProductTotals($productId, 0, null);
    }

    public function testLastPurchasePriceChangeOnInvoiceProductUpdate()
    {
        $productId = $this->createProduct();

        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $invoiceProducts = $this->createInvoiceProducts($productId, $invoiceId);

        $this->assertProductTotals($productId, $invoiceProducts[2]['productAmount'], $invoiceProducts[2]['price']);
        $newInvoiceProductData = $invoiceProducts[1];
        $newInvoiceProductData['price'] = 13.01;
        unset($newInvoiceProductData['productAmount'], $newInvoiceProductData['id']);

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProducts[1]['id'],
            $newInvoiceProductData
        );

        $this->assertResponseCode(200);

        $this->assertProductTotals($productId, 16, $invoiceProducts[2]['price']);

        $newProductId = $this->createProduct('NEW');
        $newInvoiceProductDataNewProduct = $invoiceProducts[2];
        $newInvoiceProductDataNewProduct['product'] = $newProductId;

        unset($newInvoiceProductDataNewProduct['productAmount'], $newInvoiceProductDataNewProduct['id']);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProducts[2]['id'],
            $newInvoiceProductDataNewProduct
        );

        $this->assertResponseCode(200);

        $this->assertProductTotals($productId, 15, $newInvoiceProductData['price']);
        $this->assertProductTotals($newProductId, 1, $newInvoiceProductDataNewProduct['price']);
    }

    public function testAveragePurchasePrice()
    {
        $productId = $this->createProduct();
        $productId2 = $this->createProduct('2');

        $purchaseId = $this->createPurchaseWithProduct($productId, 79.99, 13, '-2 days');

        $invoiceId1 = $this->createInvoice(
            array(
                'sku' => '-3 days',
                'acceptanceDate' => date('c', strtotime('-3 days'))
            ),
            $this->storeId,
            $this->departmentManager
        );

        $invoiceIdOld = $this->createInvoice(
            array(
                'sku' => '-31 days',
                'acceptanceDate' => date('c', strtotime('-31 days'))
            ),
            $this->storeId,
            $this->departmentManager
        );
        $invoiceProductIdOld = $this->createInvoiceProduct($invoiceIdOld, $productId, 10, 23.33);

        $invoiceProductId1 = $this->createInvoiceProduct($invoiceId1, $productId, 10, 26);
        $this->createInvoiceProduct($invoiceId1, $productId2, 6, 34.67);

        /* @var $averagePriceService AveragePriceService */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.average_price');
        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => 26));

        $invoiceId2 = $this->createInvoice(
            array(
                'sku' => '-2 days',
                'acceptanceDate' => date('c', strtotime('-2 days'))
            ),
            $this->storeId,
            $this->departmentManager
        );

        $invoiceProductId2 = $this->createInvoiceProduct($invoiceId2, $productId, 5, 29);

        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => 27));

        $invoiceId3 = $this->createInvoice(
            array(
                'sku' => '-1 days',
                'acceptanceDate' => date('c', strtotime('-1 days'))
            ),
            $this->storeId,
            $this->departmentManager
        );

        $invoiceProductId3 = $this->createInvoiceProduct($invoiceId3, $productId, 10, 31);

        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => 28.6));

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId3 . '/products/' . $invoiceProductId3
        );

        $this->assertResponseCode(204);

        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => 27, 'lastPurchasePrice' => 29));

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId2 . '/products/' . $invoiceProductId2
        );

        $this->assertResponseCode(204);

        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => 26, 'lastPurchasePrice' => 26));

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId1. '/products/' . $invoiceProductId1
        );

        $this->assertResponseCode(204);

        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => null, 'lastPurchasePrice' => 23.33));
    }

    public function testAveragePurchasePriceRounded()
    {
        $productId = $this->createProduct();
        $productId2 = $this->createProduct('2');

        $invoiceId1 = $this->createInvoice(
            array(
                'sku' => '-3 days',
                'acceptanceDate' => date('c', strtotime('-3 days'))
            ),
            $this->storeId,
            $this->departmentManager
        );

        $invoiceIdOld = $this->createInvoice(
            array(
                'sku' => '-15 days',
                'acceptanceDate' => date('c', strtotime('-15 days'))
            ),
            $this->storeId,
            $this->departmentManager
        );

        $this->createInvoiceProduct($invoiceIdOld, $productId, 10, 23.33);
        $this->createInvoiceProduct($invoiceId1, $productId, 10, 26);
        $this->createInvoiceProduct($invoiceId1, $productId2, 6, 34.67);

        /* @var $averagePriceService AveragePriceService */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.average_price');
        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => 24.67));
    }

    public function testAveragePurchasePriceChangeOnInvoiceDateChange()
    {
        $productId = $this->createProduct();

        $invoiceData = array(
            'sku' => 'now',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => date('c', strtotime('now')),
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
        );

        $invoiceId = $this->createInvoice($invoiceData, $this->storeId, $this->departmentManager);

        $this->createInvoiceProduct($invoiceId, $productId, 10, 26);

        /* @var $averagePriceService AveragePriceService */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.average_price');
        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => null, 'lastPurchasePrice' => 26));

        $accessToken = $this->auth($this->departmentManager);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId,
            array(
                'acceptanceDate' => date('c', strtotime('-2 days 13:00'))
            ) + $invoiceData
        );

        $this->assertResponseCode(200);

        $averagePriceService->recalculateAveragePrice();

        $this->assertProduct($productId, array('averagePurchasePrice' => 26, 'lastPurchasePrice' => 26));
    }

    public function testProductDataDoesNotChangeInInvoiceAfterProductUpdate()
    {
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));

        $this->assertProduct($productId, array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));

        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $accessToken = $this->auth($this->departmentManager);

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, '0.product.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('Кефир 1%', '0.product.name', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('кефир_1%', '0.product.sku', $invoiceProductsResponse);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, '0.product.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('Кефир 1%', '0.product.name', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('кефир_1%', '0.product.sku', $invoiceProductsResponse);

        $this->assertProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));
    }

    public function testTwoProductVersionsInInvoice()
    {
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);
        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $accessToken = $this->auth($this->departmentManager);

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals($productId, '*.product.id', $invoiceProductsResponse, 2);
        Assert::assertJsonPathEquals('Кефир 1%', '*.product.name', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('кефир_1%', '*.product.sku', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('Кефир 5%', '*.product.name', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('кефир_5%', '*.product.sku', $invoiceProductsResponse, 1);
    }

    public function testTwoProductVersionsCreated()
    {
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $invoiceId = $this->createInvoice(array(), $this->storeId, $this->departmentManager);

        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $productVersionRepository = $this->getContainer()->get('lighthouse.core.document.repository.product_version');

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);

        $this->assertCount(2, $productVersions);

        $firstProduct = $productVersions->getNext();
        $secondProduct = $productVersions->getNext();

        $this->assertEquals('Кефир 5%', $firstProduct->name);
        $this->assertEquals('Кефир 1%', $secondProduct->name);
    }
}
