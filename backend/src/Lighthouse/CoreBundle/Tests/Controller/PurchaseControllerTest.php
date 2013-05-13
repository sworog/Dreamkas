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
            Assert::assertJsonPathEquals($productData['product'], 'products.*.product.id', $postResponse);
            Assert::assertJsonPathEquals($productData['sellingPrice'], 'products.*.sellingPrice', $postResponse);
            Assert::assertJsonPathEquals($productData['quantity'], 'products.*.quantity', $postResponse);
        }
    }
}
