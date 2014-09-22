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
}
