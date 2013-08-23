<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class JobControllerTest extends WebTestCase
{
    public function testRecalcProductProductPrice()
    {
        $this->clearMongoDb();

        $commercialAccessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse);

        $storeId1 = $this->createStore('1');
        $storeId2 = $this->createStore('2');
        $storeId3 = $this->createStore('3');

        $productData = array(
            'sku' => 'Печенье Юбилейное',
            'purchasePrice' => 20,
            'retailMarkupMin' => 10,
            'retailMarkupMax' => 30,
            'retailPricePreference' => 'retailMarkup',
        );

        $productId = $this->createProduct($productData);

        $storeProductData1 = array(
            'retailPrice' => 22,
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId1, $productId, $storeProductData1);

        $storeProductData2 = array(
            'retailPrice' => 26,
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId2, $productId, $storeProductData2);

        $storeProductData3 = array(
            'retailPrice' => 23,
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId3, $productId, $storeProductData3);

        $updateProductData = array(
            'retailPriceMin' => 23,
            'retailPriceMax' => 34,
            'retailPricePreference' => 'retailPrice',
        ) + $productData;

        $this->updateProduct($productId, $updateProductData);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('recalc_product_price', '*.type', $getResponse);
    }
}
