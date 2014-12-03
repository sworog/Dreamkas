<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Request\StockMovementBuilder;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ReturnControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $sale = $this->factory()
            ->receipt()
                ->createSale($store, '2014-09-11T19:31:50+0400')
                ->createReceiptProduct($product->id, 10, 13.33)
            ->flush();

        $returnData = array(
            'date' => '2014-09-11T20:31:50+0400',
            'sale' => $sale->id,
            'products' => array(
                array(
                    'product' => $product->id,
                    'quantity' => 7
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $returnData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(Returne::TYPE, 'type', $response);
        Assert::assertJsonPathEquals('2014-09-11T20:31:50+0400', 'date', $response);
        Assert::assertJsonPathEquals($store->id, 'store.id', $response);

        Assert::assertJsonPathEquals($sale->id, 'sale.id', $response);

        Assert::assertJsonPathCount(1, 'products.*.id', $response);
        Assert::assertJsonPathEquals($product->id, 'products.0.product.id', $response);
        Assert::assertJsonPathEquals('7.000', 'products.0.quantity', $response);
        Assert::assertJsonPathEquals('13.33', 'products.0.price', $response);
        Assert::assertJsonPathEquals('93.31', 'products.0.totalPrice', $response);

        Assert::assertJsonPathEquals('1', 'itemsCount', $response);
        Assert::assertJsonPathEquals('93.31', 'sumTotal', $response);
    }

    /**
     * @dataProvider validationProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostWithValidationGroup($expectedCode, array $data, array $assertions = array())
    {
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $store = $this->factory()->store()->getStore();

        $sale1 = $this->factory()
            ->receipt()
                ->createSale($store)
                ->createReceiptProduct($products['1']->id, 10, 17.68)
            ->flush();

        $returnData = array(
            'date' => '',
            'sale' => $sale1->id,
            'products' => array(
                $data + array(
                    'product' => $products['1']->id,
                    'quantity' => 7
                )
            ),
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns?validate=true&validationGroups=products",
            $returnData
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
            'quantity more then quantity in sale' => array(
                400,
                array('quantity' => 11),
                array(
                    'errors.children.products.children.0.children.quantity.errors.0'
                    =>
                    'По этой позиции нельзя вернуть такое количество товара'
                )
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
     * @dataProvider validateReturnProvider
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostValidation($expectedCode, array $data, array $assertions = array())
    {
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $store = $this->factory()->store()->getStore();

        $sale1 = $this->factory()->receipt()
            ->createSale($store)
            ->createReceiptProduct($products['1']->id, 10, 17.68)
            ->flush();

        $returnData = $data + array(
            'date' => '2014-09-11T20:31:50+0400',
            'sale' => $sale1->id,
            'products' => array(
                array(
                    'product' => $products['1']->id,
                    'quantity' => 7
                )
            ),
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $returnData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($response, $assertions);
    }

    public function validateReturnProvider()
    {
        return array(
            /***********************************************************************************************
             * 'sale'
             ***********************************************************************************************/
            'empty sale' => array(
                400,
                array('sale' => ''),
                array('errors.children.sale.errors.0' => 'Укажите продажу'),
            ),
            'not valid sale' => array(
                400,
                array('sale' => '786348976387623857dd'),
                array('errors.children.sale.errors.0' => 'Продажа не найдена'),
            ),
        );
    }

    public function testTwoReturnForOneSaleQuantityValidation()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $sale = $this->factory()
            ->receipt()
                ->createSale($store, '2014-09-11T19:31:50+0400')
                ->createReceiptProduct($product->id, 10, 13.33)
            ->flush();


        $returnData = array(
            'date' => '2014-09-11T20:31:50+0400',
            'sale' => $sale->id,
            'products' => array(
                array(
                    'product' => $product->id,
                    'quantity' => 7
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $returnData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(Returne::TYPE, 'type', $response);
        Assert::assertJsonPathEquals('2014-09-11T20:31:50+0400', 'date', $response);
        Assert::assertJsonPathEquals($store->id, 'store.id', $response);

        Assert::assertJsonPathEquals($sale->id, 'sale.id', $response);

        Assert::assertJsonPathCount(1, 'products.*.id', $response);
        Assert::assertJsonPathEquals($product->id, 'products.0.product.id', $response);
        Assert::assertJsonPathEquals('7.000', 'products.0.quantity', $response);
        Assert::assertJsonPathEquals('13.33', 'products.0.price', $response);
        Assert::assertJsonPathEquals('93.31', 'products.0.totalPrice', $response);

        Assert::assertJsonPathEquals('1', 'itemsCount', $response);
        Assert::assertJsonPathEquals('93.31', 'sumTotal', $response);


        $returnData = array(
            'date' => '2014-09-12T20:31:50+0400',
            'sale' => $sale->id,
            'products' => array(
                array(
                    'product' => $product->id,
                    'quantity' => 6
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $returnData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathEquals(
            'По этой позиции нельзя вернуть такое количество товара',
            'errors.children.products.children.0.children.quantity.errors.0',
            $response
        );


        $returnData = array(
            'date' => '2014-09-12T20:31:50+0400',
            'sale' => $sale->id,
            'products' => array(
                array(
                    'product' => $product->id,
                    'quantity' => 3
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $returnData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(Returne::TYPE, 'type', $response);
        Assert::assertJsonPathEquals('2014-09-12T20:31:50+0400', 'date', $response);
        Assert::assertJsonPathEquals($store->id, 'store.id', $response);

        Assert::assertJsonPathEquals($sale->id, 'sale.id', $response);

        Assert::assertJsonPathCount(1, 'products.*.id', $response);
        Assert::assertJsonPathEquals($product->id, 'products.0.product.id', $response);
        Assert::assertJsonPathEquals('3.000', 'products.0.quantity', $response);
        Assert::assertJsonPathEquals('13.33', 'products.0.price', $response);
        Assert::assertJsonPathEquals('39.99', 'products.0.totalPrice', $response);

        Assert::assertJsonPathEquals('1', 'itemsCount', $response);
        Assert::assertJsonPathEquals('39.99', 'sumTotal', $response);


        $returnData = array(
            'date' => '2014-09-12T20:31:50+0400',
            'sale' => $sale->id,
            'products' => array(
                array(
                    'product' => $product->id,
                    'quantity' => 6
                )
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $returnData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathEquals(
            'Эта товарная позиция полностью возвращена',
            'errors.children.products.children.0.children.quantity.errors.0',
            $response
        );
    }

    public function testProductInventoryChangeOnReturn()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $this->factory()
            ->invoice()
               ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($product->id, 100, 15.00)
            ->flush();

        $this->assertStoreProductTotals($store->id, $product->id, 100, 15.00);

        $sale = $this->factory()
            ->receipt()
                ->createSale($store, '2014-09-07T08:23:12+04:00')
                ->createReceiptProduct($product->id, 90, 15)
            ->flush();

        $this->assertStoreProductTotals($store->id, $product->id, 10, 15.00);

        $this->postReturnWithOneProduct($store, $sale, '2014-09-09T08:23:12+04:00', $product->id, 10);

        $this->assertStoreProductTotals($store->id, $product->id, 20, 15.00);

        $this->postReturnWithOneProduct($store, $sale, '2014-09-09T08:24:54+04:00', $product->id, 4.555);

        $this->assertStoreProductTotals($store->id, $product->id, 24.555, 15.00);
    }

    public function testPostWithDeletedStore()
    {
        $store = $this->factory()->store()->createStore();

        $product = $this->factory()->catalog()->getProductByName();

        $this->factory()->store()->deleteStore($store);

        $saleData = StockMovementBuilder::create()
            ->addProduct($product->id, 10, 5.99)
            ->toArray();

        $accessToken = $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $saleData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Операции с участием удаленного магазина запрещены',
            'errors.errors.0',
            $postResponse
        );
    }

    /**
     * @param Store $store
     * @param Sale $sale
     * @param string $date
     * @param string $productId
     * @param float $quantity
     * @return string Return id
     */
    protected function postReturnWithOneProduct(
        Store $store,
        Sale $sale,
        $date,
        $productId,
        $quantity
    ) {
        $products = array(
            array(
                'product' => $productId,
                'quantity' => $quantity
            )
        );

        return $this->postReturn($store, $sale, $date, $products);
    }

    /**
     * @param Store $store
     * @param Sale $sale
     * @param string $date
     * @param array $products
     * @return string
     */
    protected function postReturn(
        Store $store,
        Sale $sale,
        $date,
        array $products
    ) {
        $returnData = array(
            'date' => $date,
            'sale' => $sale->id,
            'products' => $products,
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/returns",
            $returnData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);

        return $response['id'];
    }
}
