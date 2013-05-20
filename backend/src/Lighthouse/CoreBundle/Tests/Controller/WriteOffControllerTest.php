<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class WriteOffControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();

        $date = strtotime('-1 day');
        $writeOffData = array(
            'number' => '431-5678',
            'date' => date('c', $date),
            'products' => array(),
        );

        $writeOffData['products'][] = array(
            'product' => $productId,
            'quantity' => 1,
            'price' => 10.98,
            'cause' => 'Порча'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/writeoffs.json',
            array('writeOff' => $writeOffData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonHasPath('products.*.product', $postResponse);
        Assert::assertJsonPathEquals($writeOffData['number'], 'number', $postResponse);
        Assert::assertJsonPathContains(date('Y-m-d\TH:i', $date), 'date', $postResponse);
        Assert::assertJsonPathEquals($productId, 'products.*.product.id', $postResponse, 1);
    }
}
