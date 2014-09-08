<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ReceiptControllerTest extends WebTestCase
{
    /**
     * @dataProvider postProvider
     * @param string $path
     * @param string $expectedType
     */
    public function testPostAction($path, $expectedType)
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
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/{$path}",
            $saleData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals($expectedType, 'type', $response);
        Assert::assertJsonPathEquals('2014-09-09T16:23:12+0400', 'date', $response);
        Assert::assertJsonPathEquals($store->id, 'store.id', $response);

        Assert::assertJsonPathCount(1, 'products.*.id', $response);
        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $response);
        Assert::assertJsonPathEquals('10.000', 'products.0.quantity', $response);
        Assert::assertJsonPathEquals('17.68', 'products.0.price', $response);
        Assert::assertJsonPathEquals('176.80', 'products.0.totalPrice', $response);

        Assert::assertJsonPathEquals('1', 'itemsCount', $response);
        Assert::assertJsonPathEquals('176.80', 'sumTotal', $response);
    }

    /**
     * @return array
     */
    public function postProvider()
    {
        return array(
            'Sale' => array('sales', 'Sale'),
            'Return' => array('returns', 'Return'),
        );
    }

    /**
     * @dataProvider typesValidationProvider
     * @param string $type
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWithValidationGroup($type, $expectedCode, array $data, array $assertions = array())
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
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/{$type}?validate=true&validationGroups=products",
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
    public function typesValidationProvider()
    {
        return $this->applyTypesToProvider($this->validationProvider());
    }

    /**
     * @param array $providerData
     * @return array
     */
    protected function applyTypesToProvider(array $providerData)
    {
        $typedData = array();
        foreach (array('sales', 'returns') as $type) {
            foreach ($providerData as $key => $row) {
                $typedData[$type . '::' . $key] = array_merge(array($type), $row);
            }
        }
        return $typedData;
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
     * @dataProvider totalsCalculationWithValidationGroupDataProviderWithTypes
     * @param string $type
     * @param array $products
     * @param array $assertions
     */
    public function testTotalsCalculationOnPostWithValidationGroupOnPost(
        $type,
        array $products,
        array $assertions
    ) {
        $store = $this->factory()->store()->getStore();

        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $receiptData = array(
            'date' => '',
            'products' => $products
        );

        foreach ($receiptData['products'] as &$product) {
            $product['product'] = $productIds[$product['name']];
            unset($product['name']);
        }

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/{$type}?validate=true&validationGroups=products",
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
    public function totalsCalculationWithValidationGroupDataProviderWithTypes()
    {
        return $this->applyTypesToProvider($this->totalsCalculationWithValidationGroupDataProvider());
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

        $this->postReturnWithOneProduct($store, '2014-09-10T08:23:12+04:00', $productId, 3, 15.00);

        $this->assertStoreProductTotals($store->id, $productId, 88.445, 15.00);
    }

    /**
     * @param Store $store
     * @param string $date
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @return string Sale id
     */
    protected function postSaleWithOneProduct(Store $store, $date, $productId, $quantity, $price)
    {
        $products = array(
            array(
                'product' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            )
        );

        return $this->postReceipt('sales', $store, $date, $products);
    }

    /**
     * @param Store $store
     * @param string $date
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @return string Sale id
     */
    protected function postReturnWithOneProduct(Store $store, $date, $productId, $quantity, $price)
    {
        $products = array(
            array(
                'product' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            )
        );

        return $this->postReceipt('returns', $store, $date, $products);
    }

    /**
     * @param string $type
     * @param Store $store
     * @param string $date
     * @param array $products
     * @return string
     */
    protected function postReceipt($type, Store $store, $date, array $products)
    {
        $saleData = array(
            'date' => $date,
            'products' => $products
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/{$type}",
            $saleData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);

        return $response['id'];
    }
}
