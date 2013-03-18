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
    public function testPostProductAction(array $invoiceData)
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
                    'supplier' => 'ООО "Поставщик"',
                    'acceptanceDate' => '18.03.2013',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierReferenceNumber' => '1248373',
                    'createdDate' => '19.03.2013',
                    'sumTotal' => 1000,
                )
            )
        );
    }
}
