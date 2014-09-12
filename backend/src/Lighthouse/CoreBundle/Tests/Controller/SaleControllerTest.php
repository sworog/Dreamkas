<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Payment\BankCardPayment;
use Lighthouse\CoreBundle\Document\Payment\CashPayment;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class SaleControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $saleData = array(
            'date' => '2014-09-09T16:23:12+04:00',
            'products' => array(
                array(
                    'product' => $productId,
                    'quantity' => 10,
                    'price' => 17.68
                )
            ),
            'payment' => array(
                'type' => CashPayment::TYPE,
                'amountTendered' => 200
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/sales",
            $saleData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(Sale::TYPE, 'type', $response);
        Assert::assertJsonPathEquals('2014-09-09T16:23:12+0400', 'date', $response);
        Assert::assertJsonPathEquals($store->id, 'store.id', $response);

        Assert::assertJsonPathCount(1, 'products.*.id', $response);
        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $response);
        Assert::assertJsonPathEquals('10.000', 'products.0.quantity', $response);
        Assert::assertJsonPathEquals('17.68', 'products.0.price', $response);
        Assert::assertJsonPathEquals('176.80', 'products.0.totalPrice', $response);

        Assert::assertJsonPathEquals('1', 'itemsCount', $response);
        Assert::assertJsonPathEquals('176.80', 'sumTotal', $response);

        Assert::assertJsonPathEquals(CashPayment::TYPE, 'payment.type', $response);
        Assert::assertJsonPathEquals('200.00', 'payment.amountTendered', $response);
        Assert::assertJsonPathEquals('23.20', 'payment.change', $response);
    }

    /**
     * @dataProvider validationProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWithValidationGroup($expectedCode, array $data, array $assertions = array())
    {
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $store = $this->factory()->store()->getStore();

        $saleData = array(
            'date' => '',
            'products' => array(
                $data + array(
                    'product' => $productIds['1'],
                    'quantity' => 10,
                    'price' => 17.68
                )
            ),
            'payment' => array()
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/sales?validate=true&validationGroups=products",
            $saleData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($response, $assertions);
        if (400 == $expectedCode) {
            Assert::assertNotJsonHasPath('errors.children.date.errors.0', $response);
        } else {
            Assert::assertNotJsonHasPath('date', $response);
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
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'negative quantity -10' => array(
                400,
                array('quantity' => -10),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'negative quantity -1' => array(
                400,
                array('quantity' => -1),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение должно быть больше 0'
                )
            ),
            'zero quantity' => array(
                400,
                array('quantity' => 0),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
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
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой'
                )
            ),
            'float quantity very float with coma' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'float quantity very float only one message' => array(
                400,
                array('quantity' => '2,5555'),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                    'errors.children.products.children.0.children.quantity.errors.1'
                    =>
                    null
                )
            ),
            'not numeric quantity' => array(
                400,
                array('quantity' => 'abc'),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
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
                array('errors.children.products.children.0.children.price.errors.0' => 'Заполните это поле')
            ),
            'not valid price very float' => array(
                400,
                array('price' => '10,898'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('price' => '10.898'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
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
                array('errors.children.products.children.0.children.price.errors.0' => 'Значение должно быть числом'),
            ),
            'not valid price zero' => array(
                400,
                array('price' => 0),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                ),
            ),
            'not valid price negative' => array(
                400,
                array('price' => -10),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('price' => 2000000001),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('price' => '100000000'),
                array(
                    'errors.children.products.children.0.children.price.errors.0'
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
                    'errors.children.products.children.0.children.price.errors.0'
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
                array('errors.children.products.children.0.children.product.errors.0' => 'Такого товара не существует'),
            ),
            'empty product' => array(
                400,
                array('product' => ''),
                array('errors.children.products.children.0.children.product.errors.0' => 'Заполните это поле'),
            ),
        );
    }

    /**
     * @dataProvider totalsCalculationWithValidationGroupDataProvider
     * @param array $products
     * @param array $assertions
     */
    public function testTotalsCalculationOnPostWithValidationGroupOnPost(array $products, array $assertions)
    {
        $store = $this->factory()->store()->getStore();

        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $receiptData = array(
            'date' => '',
            'products' => $products,
        );

        foreach ($receiptData['products'] as &$product) {
            $product['product'] = $productIds[$product['name']];
            unset($product['name']);
        }

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/sales?validate=true&validationGroups=products",
            $receiptData
        );

        $this->assertResponseCode(201);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertNotJsonHasPath('name', $response);

        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function totalsCalculationWithValidationGroupDataProvider()
    {
        return array(
            'one product' => array(
                array(
                    array('name' => '1', 'quantity' => 1, 'price' => 10.00),
                ),
                array(
                    'sumTotal' => '10.00',
                    'itemsCount' => '1',
                    'products.0.totalPrice' => '10.00'
                )
            ),
            'three different products' => array(
                array(
                    array('name' => '1', 'quantity' => 5.678, 'price' => 13.11),
                    array('name' => '2', 'quantity' => 10, 'price' => 9.96),
                    array('name' => '3', 'quantity' => 19.1, 'price' => 0.49),
                ),
                array(
                    'products.0.totalPrice' => '74.44',
                    'products.1.totalPrice' => '99.60',
                    'products.2.totalPrice' => '9.36',
                    'itemsCount' => '3',
                    'sumTotal' => '183.40',
                ),
            ),
            'five position with duplicates' => array(
                array(
                    array('name' => '1', 'quantity' => 5.678, 'price' => 13.11),
                    array('name' => '2', 'quantity' => 10, 'price' => 9.96),
                    array('name' => '3', 'quantity' => 19.1, 'price' => 0.49),
                    array('name' => '3', 'quantity' => 6.888, 'price' => 0.49),
                    array('name' => '2', 'quantity' => 1, 'price' => 9.99),
                ),
                array(
                    'products.0.totalPrice' => '74.44',
                    'products.1.totalPrice' => '99.60',
                    'products.2.totalPrice' => '9.36',
                    'products.3.totalPrice' => '3.38',
                    'products.4.totalPrice' => '9.99',
                    'itemsCount' => '5',
                    'sumTotal' => '196.77',
                ),
            ),
        );
    }

    public function testProductInventoryChangeOnSale()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId, 100, 15.00)
            ->flush();

        $this->assertStoreProductTotals($store->id, $productId, 100, 15.00);

        $this->postSaleWithOneProduct($store, '2014-09-09T08:23:12+04:00', $productId, 10, 17.68);

        $this->assertStoreProductTotals($store->id, $productId, 90, 15.00);

        $this->postSaleWithOneProduct($store, '2014-09-09T08:24:54+04:00', $productId, 4.555, 17.68);

        $this->assertStoreProductTotals($store->id, $productId, 85.445, 15.00);
    }

    public function testGetAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $saleData = array(
            'date' => '2014-09-09T16:23:12+04:00',
            'products' => array(
                array(
                    'product' => $productId,
                    'quantity' => 10,
                    'price' => 17.68
                )
            ),
            'payment' => array(
                'type' => CashPayment::TYPE,
                'amountTendered' => 200
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/sales",
            $saleData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/sales/{$id}"
        );

        $this->assertResponseCode(200);

        $this->assertEquals($postResponse, $getResponse);
    }

    /**
     * @dataProvider cashChangeValidation
     *
     * @param $amountTendered
     * @param $expectedResponseCode
     * @param array $assertions
     */
    public function testCashChangeValidation($amountTendered, $expectedResponseCode, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $saleData = array(
            'date' => '2014-09-09T16:23:12+04:00',
            'products' => array(
                array(
                    'product' => $productId,
                    'quantity' => 10,
                    'price' => 17.68
                )
            ),
            'payment' => array(
                'type' => CashPayment::TYPE,
                'amountTendered' => $amountTendered
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/sales",
            $saleData
        );

        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @return array
     */
    public function cashChangeValidation()
    {
        return array(
            'positive' => array(
                200.00,
                201,
                array(
                    'payment.change' => '23.20',
                    'payment.amountTendered' => '200.00',
                )
            ),
            'negative' => array(
                170.00,
                400,
                array(
                    'errors.children.payment.children.amountTendered.errors.0'
                    =>
                    'Внесенная сумма должна быть равна или больше 176.80'
                )
            ),
            'exact' => array(
                176.80,
                201,
                array(
                    'payment.change' => '0.00',
                    'payment.amountTendered' => '176.80',
                )
            ),
            'empty' => array(
                null,
                400,
                array(
                    'errors.children.payment.children.amountTendered.errors.0' => 'Заполните это поле'
                )
            ),
            'invalid' => array(
                'aaa',
                400,
                array(
                    'errors.children.payment.children.amountTendered.errors.0' => 'Значение должно быть числом'
                )
            ),
            'invalid 1 000' => array(
                '1 000',
                400,
                array(
                    'errors.children.payment.children.amountTendered.errors.0' => 'Значение должно быть числом'
                )
            ),
            'invalid 200.198' => array(
                '200.198',
                400,
                array(
                    'errors.children.payment.children.amountTendered.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                )
            ),
        );
    }

    public function testPaymentBankCard()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $saleData = array(
            'date' => '2014-09-09T16:23:12+04:00',
            'products' => array(
                array(
                    'product' => $productId,
                    'quantity' => 10,
                    'price' => 17.68
                )
            ),
            'payment' => array(
                'type' => BankCardPayment::TYPE,
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/sales",
            $saleData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(BankCardPayment::TYPE, 'payment.type', $postResponse);
        Assert::assertNotJsonHasPath('payment.change', $postResponse);
        Assert::assertNotJsonHasPath('payment.sale', $postResponse);
    }

    /**
     * @dataProvider getSalesByFilterProvider
     * @param array $query
     * @param int $expectedCount
     * @param array $assertions
     */
    public function testGetSalesByFilter(array $query, $expectedCount, array $assertions = array())
    {
        $store = $this->factory()->store()->getStore();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $this->factory()
            ->receipt()
                ->createSale($store, '2014-09-11T19:31:50+04:00')
                ->createReceiptProduct($productIds['1'], 1, 49.79)
                ->createReceiptProduct($productIds['2'], 2, 19.79)
                ->createReceiptProduct($productIds['3'], 3, 9.79)
            ->persist()
                ->createSale($store, '2014-09-05T09:31:50+04:00')
                ->createReceiptProduct($productIds['1'], 2, 49.79)
            ->persist()
                ->createSale($store, '2014-09-06T05:31:50+04:00')
                ->createReceiptProduct($productIds['2'], 4, 19.79)
            ->persist()
                ->createSale($store, '2014-09-13T09:31:50+04:00')
                ->createReceiptProduct($productIds['3'], 6, 9.79)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $pairs = $this->createReplacePairs($productIds, 'product-');
        $replacedQuery = $this->replaceValues($query, $pairs);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/sales",
            null,
            $replacedQuery
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount($expectedCount, '*.id', $getResponse);

        $this->performJsonAssertions($getResponse, $assertions);
    }

    /**
     * @param array $values
     * @param string $prefix
     * @return array
     */
    protected function createReplacePairs(array $values, $prefix = '')
    {
        $pairs = array();
        foreach ($values as $key => $value) {
            $pairs["{{$prefix}{$key}}"] = $value;
        }
        return $pairs;
    }

    /**
     * @param array $values
     * @param array $pairs
     * @return array
     */
    protected function replaceValues(array $values, array $pairs)
    {
        return array_map(
            function ($value) use ($pairs) {
                return strtr($value, $pairs);
            },
            $values
        );
    }

    /**
     * @return array
     */
    public function getSalesByFilterProvider()
    {
        return array(
            '2014-09-01 - 2014-09-30' => array(
                array('dateFrom' => '2014-09-01', 'dateTo' => '2014-09-30'),
                4,
                array(
                    '0.date' => '2014-09-13T09:31:50+0400',
                    '1.date' => '2014-09-11T19:31:50+0400',
                    '2.date' => '2014-09-06T05:31:50+0400',
                    '3.date' => '2014-09-05T09:31:50+0400',
                )
            ),
            '2014-09-05 - 2014-09-07' => array(
                array('dateFrom' => '2014-09-05', 'dateTo' => '2014-09-07'),
                2,
                array(
                    '0.date' => '2014-09-06T05:31:50+0400',
                    '1.date' => '2014-09-05T09:31:50+0400',
                )
            ),
            'product 1' => array(
                array('dateFrom' => '2014-09-01', 'dateTo' => '2014-09-30', 'product' => '{product-1}'),
                2,
                array(
                    '0.date' => '2014-09-11T19:31:50+0400',
                    '1.date' => '2014-09-05T09:31:50+0400',
                )
            ),
            'product 2 with dates' => array(
                array('dateFrom' => '2014-09-01', 'dateTo' => '2014-09-07', 'product' => '{product-2}'),
                1,
                array(
                    '0.date' => '2014-09-06T05:31:50+0400',
                )
            ),
            'product 3 not in dates' => array(
                array('dateFrom' => '2014-08-01', 'dateTo' => '2014-08-02', 'product' => '{product-3}'),
                0
            )
        );
    }

    /**
     * @dataProvider getSalesFilterValidationProvider
     * @param array $query
     * @param int $expectedResponseCode
     * @param array $assertions
     */
    public function testGetSalesFilterValidation(array $query, $expectedResponseCode, array $assertions)
    {
        $store = $this->factory()->store()->getStore();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $this->factory()
            ->receipt()
                ->createSale($store, '2014-09-11T19:31:50+04:00')
                ->createReceiptProduct($productIds['1'], 1, 49.79)
                ->createReceiptProduct($productIds['2'], 2, 19.79)
                ->createReceiptProduct($productIds['3'], 3, 9.79)
            ->persist()
                ->createSale($store, '2014-09-05T09:31:50+04:00')
                ->createReceiptProduct($productIds['1'], 2, 49.79)
            ->persist()
                ->createSale($store, '2014-09-06T05:31:50+04:00')
                ->createReceiptProduct($productIds['2'], 4, 19.79)
            ->persist()
                ->createSale($store, '2014-09-13T09:31:50+04:00')
                ->createReceiptProduct($productIds['3'], 6, 9.79)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/sales",
            null,
            $query
        );

        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($getResponse, $assertions);
    }

    /**
     * @return array
     */
    public function getSalesFilterValidationProvider()
    {
        return array(
            'missing dateTo' => array(
                array('dateFrom' => '2014-09-01'),
                400,
                array('errors.children.dateTo.errors.0' => 'Заполните это поле')
            ),
            'missing dateFrom' => array(
                array('dateTo' => '2014-09-01'),
                400,
                array('errors.children.dateFrom.errors.0' => 'Заполните это поле')
            ),
            'empty query' => array(
                array(),
                400,
                array(
                    'errors.children.dateFrom.errors.0' => 'Заполните это поле',
                    'errors.children.dateTo.errors.0' => 'Заполните это поле'
                )
            ),
            'invalid dates' => array(
                array('dateFrom' => 'aaaa', 'dateTo' => 'bbb'),
                400,
                array(
                    'errors.children.dateFrom.errors.0'
                    =>
                    'Вы ввели неверную дату aaaa, формат должен быть следующий дд.мм.гггг чч:мм',
                    'errors.children.dateTo.errors.0'
                    =>
                    'Вы ввели неверную дату bbb, формат должен быть следующий дд.мм.гггг чч:мм'
                )
            ),
            'invalid product id' => array(
                array('product' => 'aaaa'),
                400,
                array(
                    'errors.children.product.errors.0' => 'Такого товара не существует',
                )
            )
        );
    }

    /**
     * @param Store $store
     * @param string $date
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $paymentType
     * @param float $amountTendered
     * @return string Sale id
     */
    protected function postSaleWithOneProduct(
        Store $store,
        $date,
        $productId,
        $quantity,
        $price,
        $paymentType = null,
        $amountTendered = null
    ) {
        $products = array(
            array(
                'product' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            )
        );

        return $this->postSale($store, $date, $products, $paymentType, $amountTendered);
    }

    /**
     * @param Store $store
     * @param string $date
     * @param array $products
     * @param float $amountTendered
     * @param string $paymentType
     * @return string
     */
    protected function postSale(Store $store, $date, array $products, $paymentType = null, $amountTendered = null)
    {
        $saleData = array(
            'date' => $date,
            'products' => $products,
            'payment' => array(
                'type' => $paymentType ?: BankCardPayment::TYPE
            )
        );

        if (null !== $amountTendered) {
            $saleData['payment']['amountTendered'] = $amountTendered;
        }

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/sales",
            $saleData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);

        return $response['id'];
    }
}
