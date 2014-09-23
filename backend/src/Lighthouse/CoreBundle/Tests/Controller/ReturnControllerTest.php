<?php

namespace Controller;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ReturnControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $store = $this->factory()->store()->getStore();
        $productId = $this->createProduct();

        $sale = $this->factory()->receipt()
            ->createSale($store, '2014-09-11T19:31:50+0400')
                ->createReceiptProduct($productId, 10, 13.33)
            ->flush();


        $returnData = array(
            'date' => '2014-09-11T20:31:50+0400',
            'sale' => $sale->id,
            'products' => array(
                array(
                    'product' => $productId,
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
        Assert::assertJsonPathEquals($productId, 'products.0.product.id', $response);
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
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $store = $this->factory()->store()->getStore();

        $sale1 = $this->factory()->receipt()
            ->createSale($store)
            ->createReceiptProduct($productIds['1'], 10, 17.68)
            ->flush();

        $returnData = array(
            'date' => '',
            'sale' => '',
            'products' => array(
                $data + array(
                    'product' => $productIds['1'],
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
            Assert::assertNotJsonHasPath('errors.children.sale.errors.0', $response);
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
                    'Нельзя вернуть больше чем было продано'
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
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $store = $this->factory()->store()->getStore();

        $sale1 = $this->factory()->receipt()
            ->createSale($store)
            ->createReceiptProduct($productIds['1'], 10, 17.68)
            ->flush();

        $returnData = $data + array(
            'date' => '2014-09-11T20:31:50+0400',
            'sale' => $sale1->id,
            'products' => array(
                array(
                    'product' => $productIds['1'],
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
}
