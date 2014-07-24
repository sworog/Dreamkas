<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StockMovementControllerTest extends WebTestCase
{
    public function testGetAction()
    {
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $invoice1 = $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-07-24 19:05:24'), $store1->id)
                    ->createInvoiceProduct($productIds['1'], 10, 14.99)
                    ->createInvoiceProduct($productIds['2'], 23.7, 13.59)
                ->flush();

        $invoice2 = $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-07-23 11:45:03'), $store2->id)
                    ->createInvoiceProduct($productIds['1'], 1, 16)
                    ->createInvoiceProduct($productIds['3'], 23.7, 13.59)
                    ->createInvoiceProduct($productIds['2'], 10.001, 12.54)
                ->flush();

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stockMovements'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $response);
        Assert::assertJsonPathEquals('Invoice', '*.type', $response, 2);
        Assert::assertJsonPathEquals($store1->id, '0.store.id', $response);
        Assert::assertJsonPathEquals($store2->id, '1.store.id', $response);
        Assert::assertJsonPathEquals($invoice1->id, '0.id', $response);
        Assert::assertJsonPathEquals($invoice2->id, '1.id', $response);
    }
}
