<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffProductsControllerTest extends WebTestCase
{
    public function testGetProductWriteOffProducts()
    {
        $store = $this->factory()->store()->getStore();

        $product1 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир 1%', 'purchasePrice' => 35.24));
        $product2 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир 5%', 'purchasePrice' => 35.64));
        $product3 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир 0%', 'purchasePrice' => 42.15));

        $writeOff1 = $this->factory()
            ->writeOff()
                ->createWriteOff($store, '2013-10-18T09:39:47+0400')
                ->createWriteOffProduct($product1->id, 100, 36.70, 'Бой')
                ->createWriteOffProduct($product2->id, 1, 12)
                ->createWriteOffProduct($product3->id, 20, 42.90, 'Бой')
            ->flush();

        $writeOff2 = $this->factory()
            ->writeOff()
                ->createWriteOff($store, '2013-10-18T12:22:00+0400')
                ->createWriteOffProduct($product1->id, 120, 37.20, 'Бой')
                ->createWriteOffProduct($product3->id, 200, 35.80, 'Бой')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product1->id}/writeOffProducts"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOff1->products[2]->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($writeOff2->products[1]->id, '*.id', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->products[0]->id, '1.id', $getResponse);
        Assert::assertJsonPathEquals($writeOff2->products[0]->id, '0.id', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->id, '1.parent.id', $getResponse);
        Assert::assertJsonPathEquals($writeOff2->id, '0.parent.id', $getResponse);
        Assert::assertNotJsonHasPath('*.store', $getResponse);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse);
    }

    public function testTotalsCalculation()
    {
        $store = $this->factory()->store()->getStore();

        $product1 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир 1%', 'purchasePrice' => 35.24));
        $product2 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир 5%', 'purchasePrice' => 35.64));
        $product3 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир 0%', 'purchasePrice' => 42.15));

        $writeOff1 = $this->factory()
            ->writeOff()
                ->createWriteOff($store, '2013-10-18T09:39:47+0400')
                ->createWriteOffProduct($product1->id, 99.99, 36.78, 'Порча')
                ->createWriteOffProduct($product2->id, 0.4, 21.77, 'Порча')
                ->createWriteOffProduct($product3->id, 7.77, 42.99, 'Порча')
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product1->id}/writeOffProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(3677.63, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(36.78, '*.price', $getResponse);
        Assert::assertJsonPathEquals(99.99, '*.quantity', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->id, '*.parent.id', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product2->id}/writeOffProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(8.71, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(0.4, '*.quantity', $getResponse);
        Assert::assertJsonPathEquals(21.77, '*.price', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->id, '*.parent.id', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product3->id}/writeOffProducts"
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals(334.03, '*.totalPrice', $getResponse);
        Assert::assertJsonPathEquals(42.99, '*.price', $getResponse);
        Assert::assertJsonPathEquals(7.77, '*.quantity', $getResponse);
        Assert::assertJsonPathEquals($writeOff1->id, '*.parent.id', $getResponse);
    }
}
