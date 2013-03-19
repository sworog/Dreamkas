<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\WebTestCase;

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

    public function testGetInvoicesAction()
    {
        $client = static::createClient();

        $postArray = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '18.03.2013',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.05.2013',
            'createdDate' => '19.03.2013',
            'sumTotal' => 1000,
        );
        for ($i = 0; $i < 5; $i++) {
            $postArray['sku'] = '12122004' . $i;
            $client->request(
                'POST',
                '/api/1/invoices',
                array('invoice' => $postArray)
            );
            $this->assertEquals(201, $client->getResponse()->getStatusCode());
            $client->restart();
        }

        $client->request(
            'GET',
            'api/1/invoices'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectCount('invoices invoice', 5, $client->getResponse()->getContent());
    }
}
