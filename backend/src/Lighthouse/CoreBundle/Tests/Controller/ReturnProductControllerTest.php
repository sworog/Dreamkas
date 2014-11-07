<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ReturnProductControllerTest extends WebTestCase
{
    public function testGetProductReturnProducts()
    {
        $store = $this->factory()->store()->getStore('197');
        $departmentManager = $this->factory()->store()->getDepartmentManager($store->id);

        $products = $this->createProductsByNames(array('1', '2', '3', '4'));

        $saleObject1 = $this->factory()
            ->receipt()
            ->createSale($store, '2012-05-12T00:00:00.000+04:00')
            ->createReceiptProduct($products['3'], 100, 513)
            ->createReceiptProduct($products['4'], 25, 180)
            ->createReceiptProduct($products['1'], 1.57, 36)
            ->createReceiptProduct($products['3'], 3.576, 36)
            ->flush();

        $this->factory()
            ->receipt()
                ->createReturn($store, '2012-05-12T19:31:44.492+04:00', $saleObject1)
                ->createReceiptProduct($products['1'], 1) //36
                ->createReceiptProduct($products['3'], 25) //36
            ->flush();

        $this->factory()
            ->receipt()
                ->createReturn($store, '2012-05-12T19:46:32.912+04:00', $saleObject1)
                ->createReceiptProduct($products['4'], 1) //180
            ->flush();

        $this->factory()
            ->receipt()
                ->createReturn($store, '2012-05-12T19:47:33.418+04:00', $saleObject1)
                ->createReceiptProduct($products['4'], 1) //180
            ->flush();

        $accessToken = $this->factory()->oauth()->auth($departmentManager);

        // Check product '1' returns
        $getResponse1 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$products['1']}/returnProducts"
        );

        $this->assertResponseCode(200);


        Assert::assertJsonPathCount(1, '*.id', $getResponse1);
        Assert::assertNotJsonPathEquals($products['2'], '*.product.id', $getResponse1);
        Assert::assertNotJsonPathEquals($products['3'], '*.product.id', $getResponse1);
        Assert::assertNotJsonPathEquals($products['4'], '*.product.id', $getResponse1);
        Assert::assertJsonPathEquals($products['1'], '0.product.id', $getResponse1);
        Assert::assertJsonPathEquals('10001', '0.product.sku', $getResponse1);
        Assert::assertJsonPathEquals($store->id, '0.parent.store.id', $getResponse1);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.parent.date', $getResponse1);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.date', $getResponse1);
        Assert::assertJsonPathEquals('36.00', '0.price', $getResponse1);
        Assert::assertJsonPathEquals('1', '0.quantity', $getResponse1);
        Assert::assertJsonPathEquals('36.00', '0.totalPrice', $getResponse1);

        Assert::assertNotJsonHasPath('*.store', $getResponse1);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse1);

        // Check product '2' returns
        $getResponse2 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$products['2']}/returnProducts"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse2);

        // Check product '3' returns
        $getResponse3 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$products['3']}/returnProducts"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse3);
        Assert::assertNotJsonPathEquals($products['1'], '*.product.id', $getResponse3);
        Assert::assertNotJsonPathEquals($products['2'], '*.product.id', $getResponse3);
        Assert::assertNotJsonPathEquals($products['4'], '*.product.id', $getResponse3);
        Assert::assertJsonPathEquals($products['3'], '0.product.id', $getResponse3);
        Assert::assertJsonPathEquals($store->id, '0.parent.store.id', $getResponse3);
        // check it same return as product '1'
        Assert::assertJsonPathEquals($getResponse1[0]['parent']['id'], '0.parent.id', $getResponse3);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.parent.date', $getResponse3);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.date', $getResponse3);
        Assert::assertJsonPathEquals('513.00', '0.price', $getResponse3);
        Assert::assertJsonPathEquals('25', '0.quantity', $getResponse3);
        Assert::assertJsonPathEquals('12825.00', '0.totalPrice', $getResponse3);

        Assert::assertNotJsonHasPath('*.store', $getResponse3);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse3);

        // Check product '4' returns
        $getResponse4 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$products['4']}/returnProducts"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse4);
        Assert::assertNotJsonPathEquals($products['1'], '*.product.id', $getResponse4);
        Assert::assertNotJsonPathEquals($products['2'], '*.product.id', $getResponse4);
        Assert::assertNotJsonPathEquals($products['3'], '*.product.id', $getResponse4);
        Assert::assertJsonPathEquals($products['4'], '*.product.id', $getResponse4, 2);

        Assert::assertJsonPathEquals($store->id, '0.parent.store.id', $getResponse4);
        Assert::assertJsonPathEquals(1, '0.parent.itemsCount', $getResponse4);
        Assert::assertJsonPathEquals('180.00', '0.parent.sumTotal', $getResponse4);
        Assert::assertJsonPathEquals(1, '1.parent.itemsCount', $getResponse4);
        Assert::assertJsonPathEquals('180.00', '1.parent.sumTotal', $getResponse4);
        Assert::assertJsonPathEquals('2012-05-12T19:47:33+0400', '0.parent.date', $getResponse4);
        Assert::assertJsonPathEquals('2012-05-12T19:46:32+0400', '1.parent.date', $getResponse4);

        Assert::assertJsonPathEquals('180.00', '0.price', $getResponse4);
        Assert::assertJsonPathEquals('1', '0.quantity', $getResponse4);
        Assert::assertJsonPathEquals('180.00', '0.totalPrice', $getResponse4);

        Assert::assertJsonPathEquals('180.00', '1.price', $getResponse4);
        Assert::assertJsonPathEquals('1', '1.quantity', $getResponse4);
        Assert::assertJsonPathEquals('180.00', '1.totalPrice', $getResponse4);

        Assert::assertNotJsonHasPath('*.store', $getResponse4);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse4);

        // check return with product '1' and '3'
        $return1 = $getResponse1[0]['parent'];
        $return3 = $getResponse3[0]['parent'];
        // unset products because
        // return3 does not have product3 but have product1
        // return1 does not have product1 but have product3
        unset($return1['products'], $return3['products']);
        $this->assertEquals($return1, $return3);
        Assert::assertJsonPathEquals(2, 'itemsCount', $return1);
        Assert::assertJsonPathEquals('12861.00', 'sumTotal', $return1);
    }
}
