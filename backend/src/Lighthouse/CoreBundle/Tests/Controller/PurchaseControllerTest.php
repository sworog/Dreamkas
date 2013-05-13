<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testPostPurchasesAction()
    {
        $this->clearMongoDb();

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');


        $purchaseData = array(
            'products' => array(
                array(
                    'product' => $product1Id,
                    'sellingPrice' => 10.11,
                    'quantity' => 5,
                ),
                array(
                    'product' => $product2Id,
                    'sellingPrice' => 22.36,
                    'quantity' => 1,
                ),
                array(
                    'product' => $product3Id,
                    'sellingPrice' => 5.99,
                    'quantity' => 2,
                ),
            )
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/purchases.json',
            array('purchase' => $purchaseData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);

        foreach ($purchaseData['products'] as $productData) {
            Assert::assertJsonPathEquals($productData['product'], 'products.*.product.id', $postResponse, 1);
            Assert::assertJsonPathEquals($productData['sellingPrice'], 'products.*.sellingPrice', $postResponse);
            Assert::assertJsonPathEquals($productData['quantity'], 'products.*.quantity', $postResponse);
        }

        Assert::assertNotJsonHasPath('products.*.purchase', $postResponse);

        $nowDate = date('Y-m-d\\TH:i');
        Assert::assertJsonPathContains($nowDate, 'createdDate', $postResponse);

        Assert::assertJsonPathEquals($postResponse['createdDate'], 'products.*.createdDate', $postResponse, 3);
    }

    public function testPostPurchasesActionWithCreatedDate()
    {
        $this->clearMongoDb();

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $createdDate = '2013-05-12T15:56:12+0400';

        $purchaseData = array(
            'createdDate' => '2013-05-12T15:56:12',
            'products' => array(
                array(
                    'product' => $product1Id,
                    'sellingPrice' => 10.11,
                    'quantity' => 5,
                ),
                array(
                    'product' => $product2Id,
                    'sellingPrice' => 22.36,
                    'quantity' => 1,
                ),
                array(
                    'product' => $product3Id,
                    'sellingPrice' => 5.99,
                    'quantity' => 2,
                ),
            )
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/purchases.json',
            array('purchase' => $purchaseData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);

        foreach ($purchaseData['products'] as $productData) {
            Assert::assertJsonPathEquals($productData['product'], 'products.*.product.id', $postResponse, 1);
            Assert::assertJsonPathEquals($productData['sellingPrice'], 'products.*.sellingPrice', $postResponse);
            Assert::assertJsonPathEquals($productData['quantity'], 'products.*.quantity', $postResponse);
        }

        Assert::assertNotJsonHasPath('products.*.purchase', $postResponse);

        Assert::assertJsonPathEquals($createdDate, 'createdDate', $postResponse);

        Assert::assertJsonPathEquals($postResponse['createdDate'], 'products.*.createdDate', $postResponse, 3);
    }
}
