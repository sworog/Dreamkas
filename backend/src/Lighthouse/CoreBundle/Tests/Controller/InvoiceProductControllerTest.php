<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Versionable\VersionRepository;

class InvoiceProductControllerTest extends WebTestCase
{
    /**
     * @param string $supplierId
     * @param string $productId
     * @param string|float $quantity
     * @param string|float $price
     * @return array
     */
    public static function getInvoiceData($supplierId, $productId, $quantity = '10', $price = '5.99')
    {
        return array(
            'supplier' => $supplierId,
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceNumber' => '1248373',
            'includesVAT' => true,
            'products' => array(
                array(
                    'quantity' => $quantity,
                    'priceEntered' => $price,
                    'product' => $productId,
                )
            )
        );
    }

    /**
     * @dataProvider productTotalPriceCalculationProvider
     */
    public function testProductTotalPriceCalculation($quantity, $price, $totalPrice)
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, $quantity, $price);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $responseJson);
        Assert::assertJsonPathEquals($quantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($price, 'products.0.priceEntered', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'products.0.totalPrice', $responseJson);
        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $responseJson);
    }

    /**
     * @return array
     */
    public function productTotalPriceCalculationProvider()
    {
        return array(
            '793.32'   => array('11',       '72.12', '793.32'),
            '72.12'    => array('1',        '72.12', '72.12'),
            '216.36'   => array('3',        '72.12', '216.36'),
            '649.08'   => array('9',        '72.12', '649.08'),
            '72192.12' => array('1001',     '72.12', '72192.12'),
            '72758.99' => array('1009',     '72.11', '72758.99'),
            '72.00'    => array('1',        '72.00', '72.00'),
            '913.70'   => array('12.671',   '72.11', '913.70'),
        );
    }

    public function testPostActionNotExistingField()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId);
        $invoiceData['products'][0]['dummy'] = 'dummy';
        $invoiceData['products'][0]['foo'] = 'foo';

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Эта форма не должна содержать дополнительных полей: "dummy", "foo"',
            'errors.0',
            $response
        );
    }

    public function testPostActionsNotExistingProduct()
    {
        $supplier = $this->factory()->supplier()->getSupplier();
        $store = $this->factory()->store()->getStore();

        $invoiceData = $this->getInvoiceData($supplier->id, 'invalid');

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Такого товара не существует',
            'children.products.children.0.children.product.errors.0',
            $response
        );
    }

    public function testInvoiceTotalsAreUpdated()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();

        $productIds = $this->createProductsBySku(array('1', '2', '3'));

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        // add first product
        $invoiceData = $this->getInvoiceData($supplier->id, $productIds[1], 10, '11.12');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        $invoiceId = $response['id'];
        Assert::assertJsonPathEquals(1, 'itemsCount', $response);
        Assert::assertJsonPathEquals(111.2, 'sumTotal', $response);

        // Add second product
        $invoiceData['products'][1] = array(
            'product' => $productIds['2'],
            'quantity' => 5,
            'priceEntered' => '12.76'
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('2', 'itemsCount', $response);
        Assert::assertJsonPathEquals('175', 'sumTotal', $response);

        // Add third product
        $invoiceData['products'][2] = array(
            'product' => $productIds['3'],
            'quantity' => 1,
            'priceEntered' => '5.99'
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('3', 'itemsCount', $response);
        Assert::assertJsonPathEquals('180.99', 'sumTotal', $response);
    }

    public function testProductAmountIsUpdatedOnInvoiceProductPost()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();

        $productId = $this->createProduct();

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        // add first product
        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, '11.12');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        $invoiceId = $response['id'];
        Assert::assertJsonPathEquals(1, 'itemsCount', $response);
        Assert::assertJsonPathEquals(111.2, 'sumTotal', $response);
        $this->assertStoreProductTotals($store->id, $productId, 10, '11.12');

        // Add second product
        $invoiceData['products'][1] = array(
            'product' => $productId,
            'quantity' => 5,
            'priceEntered' => '12.76'
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('2', 'itemsCount', $response);
        Assert::assertJsonPathEquals('175', 'sumTotal', $response);
        $this->assertStoreProductTotals($store->id, $productId, 15, '12.76');

        // Add third product
        $invoiceData['products'][2] = array(
            'product' => $productId,
            'quantity' => 1,
            'priceEntered' => '5.99'
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('3', 'itemsCount', $response);
        Assert::assertJsonPathEquals('180.99', 'sumTotal', $response);
        $this->assertStoreProductTotals($store->id, $productId, 16, '5.99');
    }

    /**
     * @dataProvider validationProvider
     */
    public function testPostActionValidation($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();
        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 17.68);

        $invoiceData['products'][0] = $data + $invoiceData['products'][0];

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
    }

    /**
     * @dataProvider validationProvider
     */
    public function testPostWithValidationGroup($expectedCode, array $data, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();
        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 17.68);
        $invoiceData['acceptanceDate'] = '';
        $invoiceData['products'][0] = $data + $invoiceData['products'][0];

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices?validate=true&validationGroups=products',
            $invoiceData
        );

        $this->assertResponseCode($expectedCode);
        $this->performJsonAssertions($response, $assertions, true);
        if (400 == $expectedCode) {
            Assert::assertNotJsonHasPath('children.acceptanceDate.errors.0', $response);
        } else {
            Assert::assertNotJsonHasPath('acceptanceDate', $response);
        }
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
                array('quantity' => 7),
            ),
            'empty quantity' => array(
                400,
                array('quantity' => ''),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'float quantity' => array(
                201,
                array('quantity' => 2.5),
            ),
            'float quantity with coma' => array(
                201,
                array('quantity' => '2,5'),
            ),
            'float quantity very float' => array(
                400,
                array('quantity' => 2.5555),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float with coma' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('quantity' => '2,5555'),
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
                array('quantity' => 'abc'),
                array(
                    'children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть числом'
                )
            ),
            /***********************************************************************************************
             * 'price'
             ***********************************************************************************************/
            'valid price dot' => array(
                201,
                array('priceEntered' => 10.89),
            ),
            'valid price dot 79.99' => array(
                201,
                array('priceEntered' => 79.99),
            ),
            'valid price coma' => array(
                201,
                array('priceEntered' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('priceEntered' => ''),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('priceEntered' => '10,898'),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('priceEntered' => '10.898'),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'valid price very float with dot' => array(
                201,
                array('priceEntered' => '10.12')
            ),
            'not valid price not a number' => array(
                400,
                array('priceEntered' => 'not a number'),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Значение должно быть числом',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('priceEntered' => 0),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                ),
            ),
            'not valid price negative' => array(
                400,
                array('priceEntered' => -10),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('priceEntered' => 2000000001),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('priceEntered' => '100000000'),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'valid price too big 10 000 000' => array(
                201,
                array('priceEntered' => '10000000'),
            ),
            'not valid price too big 10 000 001' => array(
                400,
                array('priceEntered' => '10000001'),
                array(
                    'children.products.children.0.children.priceEntered.errors.0'
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
                    'children.products.children.0.children.product.errors.0'
                    =>
                        'Такого товара не существует'
                ),
            ),
            'empty product' => array(
                400,
                array('product' => ''),
                array(
                    'children.products.children.0.children.product.errors.0'
                    =>
                    'Заполните это поле'
                ),
            ),
        );
    }

    public function testGetInvoiceProductsActionNotFound()
    {
        $store = $this->factory()->store()->getStore();
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/123484923423/products'
        );

        $this->assertResponseCode(404);
    }

    /**
     * @dataProvider putInvoiceProvider
     */
    public function testPutInvoiceProductAction($quantity, $price, $totalPrice, $newQuantity, $newPrice, $newTotalPrice)
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, $quantity, $price);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $responseJson['id'];

        Assert::assertJsonPathEquals($quantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($price, 'products.0.price', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'products.0.totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $responseJson);

        $this->assertStoreProductTotals($store->id, $productId, $quantity, $price);

        Assert::assertJsonPathEquals(1, 'itemsCount', $responseJson);
        Assert::assertJsonPathEquals($totalPrice, 'sumTotal', $responseJson);

        $this->assertInvoiceTotals($store->id, $invoiceId, $totalPrice, 1);

        $invoiceProductId = $responseJson['products'][0]['id'];

        $invoiceData['products'][0] = array(
            'id' => $invoiceProductId,
            'quantity' => $newQuantity,
            'priceEntered' => $newPrice,
        ) + $invoiceData['products'][0];

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);
        Assert::assertNotJsonPathEquals($invoiceProductId, 'products.0.id', $responseJson);
        Assert::assertJsonPathEquals($newPrice, 'products.0.price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'products.0.totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $responseJson);

        $this->assertStoreProductTotals($store->id, $productId, $newQuantity, $newPrice);
        $this->assertInvoiceTotals($store->id, $invoiceId, $newTotalPrice, 1);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($newPrice, 'products.0.price', $responseJson);
        Assert::assertJsonPathEquals($newQuantity, 'products.0.quantity', $responseJson);
        Assert::assertJsonPathEquals($newTotalPrice, 'products.0.totalPrice', $responseJson);

        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $responseJson);
        Assert::assertJsonPathEquals($invoiceId, 'id', $responseJson);
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
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product1Id = $this->createProduct('_1');
        $product2Id = $this->createProduct('_2');

        $this->assertStoreProductTotals($store->id, $product1Id, 0, null);
        $this->assertStoreProductTotals($store->id, $product2Id, 0, null);

        $invoiceData = $this->getInvoiceData($supplier->id, $product1Id, $quantity1, $price1);

        // POST invoice product
        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $postJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceId = $postJson['id'];

        $this->assertStoreProductTotals($store->id, $product1Id, $quantity1, $price1);

        Assert::assertJsonPathEquals($invoiceSumTotal1, 'sumTotal', $postJson);
        Assert::assertJsonPathEquals(1, 'itemsCount', $postJson);

        $this->assertStoreProductTotals($store->id, $product1Id, $quantity1, $price1);
        $this->assertStoreProductTotals($store->id, $product2Id, 0, null);
        $this->assertInvoiceTotals($store->id, $invoiceId, $invoiceSumTotal1, 1);

        // PUT invoice product with another product id
        $invoiceData['products'][0] = array(
            'quantity' => $quantity2,
            'priceEntered' => $price2,
            'product'  => $product2Id,
        ) + $invoiceData['products'][0];

        $putJson = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($invoiceSumTotal2, 'sumTotal', $putJson);
        Assert::assertJsonPathEquals(1, 'itemsCount', $putJson);

        $this->assertStoreProductTotals($store->id, $product2Id, $quantity2, $price2);
        $this->assertStoreProductTotals($store->id, $product1Id, 0, null);
        $this->assertInvoiceTotals($store->id, $invoiceId, $invoiceSumTotal2, 1);
    }

    /**
     * @param string $storeId
     * @param string $invoiceId
     * @param string $sumTotal
     * @param int $itemsCount
     * @throws \PHPUnit_Framework_ExpectationFailedException
     * @throws \PHPUnit_Framework_Exception
     */
    protected function assertInvoiceTotals($storeId, $invoiceId, $sumTotal, $itemsCount)
    {
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $invoiceJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId
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
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productIds = $this->createProductsBySku(array('1', '2', '3'));
        $invoiceData = $this->getInvoiceData($supplier->id, $productIds['1'], 1, 1.99);
        $invoiceData['products'][1] = array(
            'quantity' => 2,
            'priceEntered' => 2.99,
            'product' => $productIds['2']
        );
        $invoiceData['products'][2] = array(
            'quantity' => 3,
            'priceEntered' => 3.99,
            'product' => $productIds['3']
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathCount(3, 'products.*.id', $postResponse);
        Assert::assertJsonPathEquals(19.94, 'sumTotal', $postResponse);
        Assert::assertJsonPathEquals(3, 'itemsCount', $postResponse);

        $invoiceId = $postResponse['id'];
        $invoiceData['products'][0]['id'] = $postResponse['products'][0]['id'];
        $invoiceData['products'][1]['id'] = $postResponse['products'][1]['id'];
        unset($invoiceData['products'][1]);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, 'products.*.id', $putResponse);
        Assert::assertNotJsonPathEquals($productIds['2'], 'products.*.id', $putResponse);
        Assert::assertJsonPathEquals(13.96, 'sumTotal', $putResponse);
        Assert::assertJsonPathEquals(2, 'itemsCount', $putResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, 'products.*.id', $getResponse);
        Assert::assertJsonPathEquals(13.96, 'sumTotal', $getResponse);
        Assert::assertJsonPathEquals(2, 'itemsCount', $getResponse);
    }

    public function testDeleteProductsActionUpdateAmountAndInvoiceTotals()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 11.12);
        $invoiceData['products'][1] = array(
            'product' => $productId,
            'quantity' => 5,
            'priceEntered' => 12.76,
        );
        $invoiceData['products'][2] = array(
            'product' => $productId,
            'quantity' => 1,
            'priceEntered' => 5.99,
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $this->assertStoreProductTotals($store->id, $productId, 16, 5.99);

        $this->assertInvoiceTotals($store->id, $invoiceId, 180.99, 3);

        unset($invoiceData['products'][1]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, 'products.*.id', $getResponse);
        Assert::assertNotJsonPathEquals(5, 'products.*.quantity', $getResponse);

        $this->assertStoreProductTotals($store->id, $productId, 11, 5.99);
        $this->assertInvoiceTotals($store->id, $invoiceId, 117.19, 2);
    }

    public function testLastPurchasePriceChangeOnInvoiceProductDelete()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 11.12);
        $invoiceData['products'][1] = array(
            'product' => $productId,
            'quantity' => 5,
            'priceEntered' => 12.76
        );
        $invoiceData['products'][2] = array(
            'product' => $productId,
            'quantity' => 1,
            'priceEntered' => 5.99
        );
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $this->assertStoreProductTotals($store->id, $productId, 16, 5.99);

        unset($invoiceData['products'][2]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $productId, 15, 12.76);

        unset($invoiceData['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $productId, 5, 12.76);
    }

    public function testLastPurchasePriceChangeOnInvoiceProductUpdate()
    {
        $supplier = $this->factory()->supplier()->getSupplier();
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 11.12);
        $invoiceData['products'][1] = array(
            'product' => $productId,
            'quantity' => 5,
            'priceEntered' => 12.76
        );
        $invoiceData['products'][2] = array(
            'product' => $productId,
            'quantity' => 1,
            'priceEntered' => 5.99
        );
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $this->assertStoreProductTotals($store->id, $productId, 16, 5.99);

        $invoiceData['products'][1]['priceEntered'] = 13.01;

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $productId, 16, 5.99);

        $newProductId = $this->createProduct('NEW');
        $invoiceData['products'][2]['product'] = $newProductId;

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);

        $this->assertStoreProductTotals($store->id, $productId, 15, 13.01);
        $this->assertStoreProductTotals($store->id, $newProductId, 1, 5.99);
    }

    public function testAveragePurchasePrice()
    {
        $supplier = $this->factory()->supplier()->getSupplier();
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');

        $invoiceData0 = $this->getInvoiceData($supplier->id, $productId1, 10, 23.33);
        $invoiceData0['acceptanceDate'] = date('c', strtotime('-31 days'));

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData0
        );
        $this->assertResponseCode(201);

        $invoiceData1 = $this->getInvoiceData($supplier->id, $productId1, 10, 26);
        $invoiceData1['acceptanceDate'] = date('c', strtotime('-3 days'));
        $invoiceData1['products'][1] = array(
            'product' => $productId2,
            'priceEntered' => 34.67,
            'quantity' => 6
        );
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData1
        );
        $this->assertResponseCode(201);
        $invoiceId1 = $response['id'];

        /* @var $averagePriceService StoreProductMetricsCalculator */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.product.metrics_calculator');
        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId1, array('averagePurchasePrice' => 26));

        $invoiceData2 = $this->getInvoiceData($supplier->id, $productId1, 5, 29);
        $invoiceData2['acceptanceDate'] = date('c', strtotime('-2 days'));
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData2
        );
        $this->assertResponseCode(201);
        $invoiceId2 = $response['id'];

        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId1, array('averagePurchasePrice' => 27));

        $invoiceData3 = $this->getInvoiceData($supplier->id, $productId1, 10, 31);
        $invoiceData3['acceptanceDate'] = date('c', strtotime('-1 days'));
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData3
        );
        $this->assertResponseCode(201);
        $invoiceId3 = $response['id'];

        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId1, array('averagePurchasePrice' => 28.6));

        unset($invoiceData3['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId3,
            $invoiceData3
        );

        $this->assertResponseCode(200);

        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId1,
            array('averagePurchasePrice' => 27, 'lastPurchasePrice' => 29)
        );

        unset($invoiceData2['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId2,
            $invoiceData2
        );

        $this->assertResponseCode(200);

        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId1,
            array('averagePurchasePrice' => 26, 'lastPurchasePrice' => 26)
        );

        unset($invoiceData1['products'][0]);
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId1,
            $invoiceData1
        );

        $this->assertResponseCode(200);

        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId1,
            array('averagePurchasePrice' => null, 'lastPurchasePrice' => 23.33)
        );
    }

    public function testAveragePurchasePriceRounded()
    {
        $store = $this->factory()->store()->getStore();

        $productId = $this->createProduct();
        $productId2 = $this->createProduct('2');

        $invoiceId1 = $this->createInvoice(array('acceptanceDate' => date('c', strtotime('-3 days'))), $store->id);

        $invoiceIdOld = $this->createInvoice(array('acceptanceDate' => date('c', strtotime('-15 days'))), $store->id);

        $this->createInvoiceProduct($invoiceIdOld, $productId, 10, 23.33);
        $this->createInvoiceProduct($invoiceId1, $productId, 10, 26);
        $this->createInvoiceProduct($invoiceId1, $productId2, 6, 34.67);

        /* @var $averagePriceService StoreProductMetricsCalculator */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.product.metrics_calculator');
        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct($store->id, $productId, array('averagePurchasePrice' => 24.67));
    }

    public function testAveragePurchasePriceChangeOnInvoiceDateChange()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 10, 26);
        $invoiceData['acceptanceDate'] = date('c', strtotime('now'));
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        /* @var $averagePriceService StoreProductMetricsCalculator */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.product.metrics_calculator');
        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId,
            array(
                'averagePurchasePrice' => null,
                'lastPurchasePrice' => 26
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            array(
                'acceptanceDate' => date('c', strtotime('-2 days 13:00'))
            ) + $invoiceData
        );

        $this->assertResponseCode(200);

        $averagePriceService->recalculateAveragePrice();

        $this->assertStoreProduct(
            $store->id,
            $productId,
            array(
                'averagePurchasePrice' => 26,
                'lastPurchasePrice' => 26
            )
        );
    }

    public function testProductDataDoesNotChangeInInvoiceAfterProductUpdate()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));

        $this->assertProduct($productId, array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));

        $invoiceId = $this->createInvoice(array(), $store->id);
        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('Кефир 1%', 'products.0.product.name', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('кефир_1%', 'products.0.product.sku', $invoiceProductsResponse);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('Кефир 1%', 'products.0.product.name', $invoiceProductsResponse);
        Assert::assertJsonPathEquals('кефир_1%', 'products.0.product.sku', $invoiceProductsResponse);

        $this->assertProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));
    }

    public function testTwoProductVersionsInInvoice()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $invoiceId = $this->createInvoice(array(), $store->id);
        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->factory->clear();
        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $invoiceProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, 'products.*.id', $invoiceProductsResponse);
        Assert::assertJsonPathEquals($productId, 'products.*.product.id', $invoiceProductsResponse, 2);
        Assert::assertJsonPathEquals('Кефир 1%', 'products.*.product.name', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('кефир_1%', 'products.*.product.sku', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('Кефир 5%', 'products.*.product.name', $invoiceProductsResponse, 1);
        Assert::assertJsonPathEquals('кефир_5%', 'products.*.product.sku', $invoiceProductsResponse, 1);
    }

    public function testTwoProductVersionsCreated()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%'));
        $invoiceId = $this->createInvoice(array(), $store->id);

        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        $this->updateProduct($productId, array('name' => 'Кефир 5%', 'sku' => 'кефир_5%'));

        $this->factory->clear();
        $this->createInvoiceProduct($invoiceId, $productId, 10, 10.12);

        /* @var VersionRepository $productVersionRepository */
        $productVersionRepository = $this->getContainer()->get('lighthouse.core.document.repository.product_version');

        $productVersions = $productVersionRepository->findAllByDocumentId($productId);

        $this->assertCount(2, $productVersions);

        $firstProduct = $productVersions->getNext();
        $secondProduct = $productVersions->getNext();

        $this->assertEquals('Кефир 5%', $firstProduct->name);
        $this->assertEquals('Кефир 1%', $secondProduct->name);
    }

    public function testGetProductInvoiceProducts()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%', 'purchasePrice' => 35.24));
        $productId2 = $this->createProduct(array('name' => 'Кефир 5%', 'sku' => 'кефир_5%', 'purchasePrice' => 35.64));
        $productId3 = $this->createProduct(array('name' => 'Кефир 0%', 'sku' => 'кефир_0%', 'purchasePrice' => 42.15));

        $invoiceId1 = $this->createInvoice(array('acceptanceDate' => '2013-10-18T09:39:47+0400'), $store->id);
        $invoiceProductId11 = $this->createInvoiceProduct($invoiceId1, $productId1, 100, 36.70);
        $invoiceProductId13 = $this->createInvoiceProduct($invoiceId1, $productId3, 20, 42.90);

        $invoiceId2 = $this->createInvoice(array('acceptanceDate' => '2013-10-18T12:22:00+0400'), $store->id);
        $invoiceProductId21 = $this->createInvoiceProduct($invoiceId2, $productId1, 120, 37.20);
        $invoiceProductId22 = $this->createInvoiceProduct($invoiceId2, $productId2, 200, 35.80);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/invoiceProducts'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($invoiceProductId13, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($invoiceProductId22, '*.id', $getResponse);
        Assert::assertJsonPathEquals($invoiceProductId11, '1.id', $getResponse);
        Assert::assertJsonPathEquals($invoiceProductId21, '0.id', $getResponse);
        Assert::assertJsonPathEquals($invoiceId1, '1.invoice.id', $getResponse);
        Assert::assertJsonPathEquals($invoiceId2, '0.invoice.id', $getResponse);
        Assert::assertNotJsonHasPath('*.store', $getResponse);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse);

        Assert::assertJsonHasPath('0.acceptanceDate', $getResponse);
        Assert::assertJsonHasPath('0.invoice.acceptanceDate', $getResponse);
        $this->assertEquals($getResponse[0]['acceptanceDate'], $getResponse[0]['invoice']['acceptanceDate']);

        Assert::assertJsonHasPath('1.acceptanceDate', $getResponse);
        Assert::assertJsonHasPath('1.invoice.acceptanceDate', $getResponse);
        $this->assertEquals($getResponse[1]['acceptanceDate'], $getResponse[1]['invoice']['acceptanceDate']);
    }

    public function testInvoiceProductTotalPriceWithFloatQuantity()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%', 'purchasePrice' => 35.24));
        $productId2 = $this->createProduct(array('name' => 'Кефир 5%', 'sku' => 'кефир_5%', 'purchasePrice' => 35.64));
        $productId3 = $this->createProduct(array('name' => 'Кефир 0%', 'sku' => 'кефир_0%', 'purchasePrice' => 42.15));

        $invoiceId1 = $this->createInvoice(array('acceptanceDate' => '2013-10-18T09:39:47+0400'), $store->id);

        $this->createInvoiceProduct($invoiceId1, $productId1, 99.99, 36.78);
        $this->createInvoiceProduct($invoiceId1, $productId2, 0.4, 21.77);
        $this->createInvoiceProduct($invoiceId1, $productId3, 7.77, 42.99);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(36.78, "*.price", $getResponse);
        Assert::assertJsonPathEquals(99.99, "*.quantity", $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId2 . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(8.71, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(0.4, "*.quantity", $getResponse);
        Assert::assertJsonPathEquals(21.77, "*.price", $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId3 . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(334.03, "*.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(42.99, "*.price", $getResponse);
        Assert::assertJsonPathEquals(7.77, "*.quantity", $getResponse);
    }

    public function testInvoiceProductWithVATFields()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%', 'purchasePrice' => 35.24));

        $invoiceId1 = $this->createInvoice(
            array(
                'acceptanceDate' => '2013-10-18T09:39:47+0400',
                'includesVAT' => true,
            ),
            $store->id
        );

        $this->createInvoiceProduct($invoiceId1, $productId1, 99.99, 36.78);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, "0.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(3343.66, "0.totalPriceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(333.97, "0.totalAmountVAT", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.priceEntered", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.price", $getResponse);
        Assert::assertJsonPathEquals(33.44, "0.priceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(3.34, "0.amountVAT", $getResponse);
        Assert::assertJsonPathEquals(99.99, "0.quantity", $getResponse);
    }

    /**
     * Проверяем что указав цену без НДС получим данные соответствующие данным теста выше
     */
    public function testInvoiceProductWithVATFieldsWithoutVAT()
    {
        $store = $this->factory()->store()->getStore();
        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%', 'purchasePrice' => 35.24));

        $invoiceId1 = $this->createInvoice(
            array(
                'acceptanceDate' => '2013-10-18T09:39:47+0400',
                'includesVAT' => false,
            ),
            $store->id
        );

        $this->createInvoiceProduct($invoiceId1, $productId1, 99.99, 33.44);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId1 . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, "0.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(3343.66, "0.totalPriceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(333.97, "0.totalAmountVAT", $getResponse);
        Assert::assertJsonPathEquals(33.44, "0.priceEntered", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.price", $getResponse);
        Assert::assertJsonPathEquals(33.44, "0.priceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(3.34, "0.amountVAT", $getResponse);
        Assert::assertJsonPathEquals(99.99, "0.quantity", $getResponse);
    }

    public function testInvoiceProductVATFieldChangeIncludesVATInInvoice()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct(array('name' => 'Кефир 1%', 'sku' => 'кефир_1%', 'purchasePrice' => 35.24));

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 99.99, 36.78);
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $invoiceResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $invoiceResponse['id'];

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, "0.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(3343.66, "0.totalPriceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(333.97, "0.totalAmountVAT", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.priceEntered", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.price", $getResponse);
        Assert::assertJsonPathEquals(33.44, "0.priceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(3.34, "0.amountVAT", $getResponse);
        Assert::assertJsonPathEquals(99.99, "0.quantity", $getResponse);


        $invoiceData['includesVAT'] = false;

        $invoiceResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals(false, 'includesVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(4045.60, 'sumTotal', $invoiceResponse);
        Assert::assertJsonPathEquals(3677.63, 'sumTotalWithoutVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(367.96, 'totalAmountVAT', $invoiceResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(4045.60, "0.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(3677.63, "0.totalPriceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(367.96, "0.totalAmountVAT", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.priceEntered", $getResponse);
        Assert::assertJsonPathEquals(40.46, "0.price", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.priceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(3.68, "0.amountVAT", $getResponse);
        Assert::assertJsonPathEquals(99.99, "0.quantity", $getResponse);

        $invoiceData['includesVAT'] = true;

        $invoiceResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals(true, 'includesVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(3677.63, 'sumTotal', $invoiceResponse);
        Assert::assertJsonPathEquals(3343.66, 'sumTotalWithoutVAT', $invoiceResponse);
        Assert::assertJsonPathEquals(333.97, 'totalAmountVAT', $invoiceResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/products/' . $productId . '/invoiceProducts'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, "0.totalPrice", $getResponse);
        Assert::assertJsonPathEquals(3343.66, "0.totalPriceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(333.97, "0.totalAmountVAT", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.priceEntered", $getResponse);
        Assert::assertJsonPathEquals(36.78, "0.price", $getResponse);
        Assert::assertJsonPathEquals(33.44, "0.priceWithoutVAT", $getResponse);
        Assert::assertJsonPathEquals(3.34, "0.amountVAT", $getResponse);
        Assert::assertJsonPathEquals(99.99, "0.quantity", $getResponse);
    }

    public function testPutWithEmptyQuantity()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $productId = $this->createProduct();

        $invoiceData = $this->getInvoiceData($supplier->id, $productId, 1, 9.99);

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/invoices',
            $invoiceData
        );
        $this->assertResponseCode(201);
        $invoiceId = $response['id'];

        $invoiceData['products'][0]['quantity'] = '';
        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $store->id . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Заполните это поле',
            'children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    public function testGetInvoiceProductAfterEditInvoiceAcceptanceDate()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $productId = $this->createProduct();
        $invoiceId = $this->createInvoice(array('acceptanceDate' => '2014-01-10T12:33:33+0400'), $storeId);
        $this->createInvoiceProduct($invoiceId, $productId, 1, 9.99, $storeId);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('2014-01-10T12:33:33+0400', 'acceptanceDate', $response);
        Assert::assertJsonPathEquals('2014-01-10T12:33:33+0400', 'products.0.acceptanceDate', $response);

        $this->factory()->clear();
        $this->editInvoice(array('acceptanceDate' => '2014-01-03T10:11:10+0400'), $invoiceId, $storeId);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('2014-01-03T10:11:10+0400', 'acceptanceDate', $response);
        Assert::assertJsonPathEquals('2014-01-03T10:11:10+0400', 'products.0.acceptanceDate', $response);
    }

    public function testProductsActionProductCategoryIsNotExposed()
    {
        $storeId = $this->factory()->store()->getStoreId();

        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $invoiceId = $this->createInvoice(array(), $storeId);

        $this->createInvoiceProduct($invoiceId, $productId1, 2, 9.99, $storeId);
        $this->createInvoiceProduct($invoiceId, $productId2, 3, 4.99, $storeId);
        $this->createInvoiceProduct($invoiceId, $productId3, 2, 1.95, $storeId);

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId .'/invoices/' . $invoiceId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('products.*.product.subCategory', $getResponse);
        Assert::assertNotJsonHasPath('products.*.invoice', $getResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category.group', $getResponse);
        Assert::assertJsonPathCount(0, 'products.*.invoice.products.*.id', $getResponse);
        Assert::assertNotJsonHasPath('products.*.product.subCategory.category', $getResponse);
    }
}
