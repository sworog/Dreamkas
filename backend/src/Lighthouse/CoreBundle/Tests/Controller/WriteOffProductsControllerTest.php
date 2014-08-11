<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffProductsControllerTest extends WebTestCase
{
    public function testProductWriteOffProductsAction()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct(array('name' => 'Кефир 1%', 'purchasePrice' => 35.24));
        $productId2 = $this->createProduct(array('name' => 'Кефир 5%', 'purchasePrice' => 35.64));
        $productId3 = $this->createProduct(array('name' => 'Кефир 0%', 'purchasePrice' => 42.15));

        $writeOff1 = $this->factory()
            ->writeOff()
                ->createWriteOff($store, '2013-10-18T09:39:47+0400')
                ->createWriteOffProduct($productId1, 99.99, 36.78, 'Порча')
                ->createWriteOffProduct($productId2, 0.4, 21.77, 'Порча')
                ->createWriteOffProduct($productId3, 7.77, 42.99, 'Порча')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$productId1}/writeOffProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(36.78, '*.price', $getResponse);
        Assert::assertJsonPathEquals(99.99, '*.quantity', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->id, '*.writeOff.id', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$productId2}/writeOffProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(8.71, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(0.4, '*.quantity', $getResponse);
        Assert::assertJsonPathEquals(21.77, '*.price', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->id, '*.writeOff.id', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$productId3}/writeOffProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(334.03, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(42.99, '*.price', $getResponse);
        Assert::assertJsonPathEquals(7.77, '*.quantity', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->id, '*.writeOff.id', $getResponse);
    }
}
