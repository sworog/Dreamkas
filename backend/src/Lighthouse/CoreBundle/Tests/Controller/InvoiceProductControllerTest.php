<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class InvoiceProductControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider postActionProvider
     */
    public function testPostAction($quantity, $price, $totalPrice)
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = $this->createProduct();

        $invoiceProductData = array(
            'quantity' => $quantity,
            'price'    => $price,
            'product'  => $productId,
        );

        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            'api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode(), $this->client->getResponse());
        $this->assertArrayHasKey('id', $response);
        $this->assertNotEmpty($response['id']);
        $this->assertArrayHasKey('quantity', $response);
        $this->assertEquals($quantity, $response['quantity']);
        $this->assertArrayHasKey('price', $response);
        $this->assertEquals($price, $response['price']);
        $this->assertArrayHasKey('totalPrice', $response);
        $this->assertEquals($totalPrice, $response['totalPrice']);
        $this->assertArrayHasKey('invoice', $response);
        $this->assertArrayHasKey('product', $response);
    }

    /**
     * @return array
     */
    public function postActionProvider()
    {
        return array(
            array(11,   '72.12', '793.32'),
            array(1,    '72.12', '72.12'),
            array(3,    '72.12', '216.36'),
            array(9,    '72.12', '649.08'),
            array(1001, '72.12', '72192.12'),
            array(1009, '72.11', '72758.99'),
            array(1,    '72.00', '72.00'),
        );
    }

    public function testPostActionNotExistingInvoice()
    {
        $this->clearMongoDb();

        $invoiceId = 'dwedwdwdwwd';
        $productId = 'dewdewdwedw';

        $invoiceProductData = array(
            'quantity' => 10,
            'product'  => $productId,
        );

        $this->clientJsonRequest(
            $this->client,
            'POST',
            'api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testPostActionNotExistingProduct()
    {
        $this->clearMongoDb();

        $invoiceId = $this->createInvoice();
        $productId = 'dewdewdwedw';

        $invoiceProductData = array(
            'quantity' => 10,
            'product'  => $productId,
        );

        $response = $this->clientJsonRequest(
            $this->client,
            'POST',
            'api/1/invoices/' . $invoiceId . '/products.json',
            array('invoiceProduct' => $invoiceProductData)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['children']['product']['errors'][0]));
        $this->assertContains('Продукт с id', $response['children']['product']['errors'][0]);
        $this->assertContains('не существует', $response['children']['product']['errors'][0]);
    }

    /**
     * @return string
     */
    protected function createInvoice()
    {
        $invoiceData = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
        );

        $crawler = $this->client->request(
            'POST',
            'api/1/invoices',
            array('invoice' => $invoiceData)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $invoiceId = $crawler->filterXPath("//invoice/id")->text();

        $this->client->restart();

        return $invoiceId;
    }

    /**
     * @return string
     */
    protected function createProduct()
    {
        $productData = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
        );

        $crawler = $this->client->request(
            'POST',
            'api/1/products',
            array('product' => $productData)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $productId = $crawler->filterXPath("//product/id")->text();

        $this->client->restart();

        return $productId;
    }
}
