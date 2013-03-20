<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

class InvoiceControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
    }

    /**
     * @dataProvider invoiceDataProvider
     */
    public function testPostInvoiceAction(array $invoiceData)
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            'api/1/invoices',
            array('invoice' => $invoiceData)
        );

        $content = $client->getResponse()->getContent();
        $this->assertEquals(201, $client->getResponse()->getStatusCode(), $content);

        foreach ($invoiceData as $property => $expected) {
            $actual = $crawler->filterXPath("//invoice/$property")->text();
            $this->assertEquals($expected, $actual);
        }
    }

    public function invoiceDataProvider()
    {
        return array(
            'invoice data' => array(
                array(
                    'sku' => 'sdfwfsf232',
                    'supplier' => 'ООО "Поставщик"',
                    'acceptanceDate' => '18.03.2013',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceSku' => '1248373',
                    'supplierInvoiceDate' => '17.05.2013',
                    'createdDate' => '19.03.2013',
                    'sumTotal' => 1000,
                )
            )
        );
    }

    /**
     * @dataProvider invoiceDataProvider
     */
    public function testGetInvoicesAction(array $invoiceData)
    {
        $client = static::createClient();

        for ($i = 0; $i < 5; $i++) {
            $invoiceData['sku'] = '12122004' . $i;
            $this->createInvoice($invoiceData, $client);
        }

        $client->request(
            'GET',
            'api/1/invoices'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectCount('invoices invoice', 5, $client->getResponse()->getContent());
    }

    /**
     * @dataProvider invoiceDataProvider
     */
    public function testGetInvoice(array $invoiceData)
    {
        $client = static::createClient();

        $id = $this->createInvoice($invoiceData, $client);

        $crawler = $client->request(
            'GET',
            '/api/1/invoices/' . $id
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        foreach ($invoiceData as $property => $expected) {
            $actual = $crawler->filterXPath("//invoice/$property")->text();
            $this->assertEquals($expected, $actual);
        }
    }

    public function testGetInvoiceNotFound()
    {
        $client = static::createClient();

        $id = 'not_exists_id';
        $crawler = $client->request(
            'GET',
            '/api/1/invoices/' . $id
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @param array $invoiceData
     * @param Client $client
     * @return string
     */
    protected function createInvoice(array $invoiceData, $client)
    {
        /** @var Crawler $crawler  */
        $crawler = $client->request(
            'POST',
            '/api/1/invoices',
            array('invoice' => $invoiceData)
        );
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $client->restart();

        return $crawler->filterXPath("//invoice/id")->text();
    }
}
