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

    /**
     * @dataProvider validateProvider
     */
    public function testPostInvoiceValidation($expectedCode, array $data, array $assertions = array())
    {
        $client = static::createClient();

        $invoiceData = $this->invoiceDataProvider();

        $postData = array_merge($invoiceData['invoice data'][0], $data);

        $crawler = $client->request(
            'POST',
            'api/1/invoices',
            array('invoice' => $postData)
        );

        $this->assertEquals(
            $expectedCode,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        foreach ($assertions as $selector => $expected) {
            $this->assertContains($expected, $crawler->filter($selector)->first()->text());
        }
    }

    /**
     * @return array
     */
    public function validateProvider()
    {
        return array(
            /***********************************************************************************************
             * 'sku'
             ***********************************************************************************************/
            'valid sku' => array(
                201,
                array('sku' => 'sku'),
            ),
            'valid sku 100 chars' => array(
                201,
                array('sku' => str_repeat('z', 100)),
            ),
            'empty sku' => array(
                400,
                array('sku' => ''),
                array(
                    'form[name="invoice"] form[name="sku"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid sku too long' => array(
                400,
                array('sku' => str_repeat("z", 105)),
                array(
                    'form[name="invoice"] form[name="sku"] errors entry'
                    =>
                    'Не более 100 символов',
                ),
            ),
            /***********************************************************************************************
             * 'supplier'
             ***********************************************************************************************/
            'valid supplier' => array(
                201,
                array('supplier' => 'supplier'),
            ),
            'valid supplier 300 chars' => array(
                201,
                array('supplier' => str_repeat('z', 300)),
            ),
            'empty supplier' => array(
                400,
                array('supplier' => ''),
                array(
                    'form[name="invoice"] form[name="supplier"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid supplier too long' => array(
                400,
                array('supplier' => str_repeat("z", 305)),
                array(
                    'form[name="invoice"] form[name="supplier"] errors entry'
                    =>
                    'Не более 300 символов',
                ),
            ),
            /***********************************************************************************************
             * 'accepter'
             ***********************************************************************************************/
            'valid accepter' => array(
                201,
                array('accepter' => 'accepter'),
            ),
            'valid accepter 100 chars' => array(
                201,
                array('accepter' => str_repeat('z', 100)),
            ),
            'empty accepter' => array(
                400,
                array('accepter' => ''),
                array(
                    'form[name="invoice"] form[name="accepter"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid accepter too long' => array(
                400,
                array('accepter' => str_repeat("z", 105)),
                array(
                    'form[name="invoice"] form[name="accepter"] errors entry'
                    =>
                    'Не более 100 символов',
                ),
            ),
            /***********************************************************************************************
             * 'legalEntity'
             ***********************************************************************************************/
            'valid legalEntity' => array(
                201,
                array('legalEntity' => 'legalEntity'),
            ),
            'valid legalEntity 300 chars' => array(
                201,
                array('legalEntity' => str_repeat('z', 300)),
            ),
            'empty legalEntity' => array(
                400,
                array('legalEntity' => ''),
                array(
                    'form[name="invoice"] form[name="legalEntity"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid legalEntity too long' => array(
                400,
                array('legalEntity' => str_repeat("z", 305)),
                array(
                    'form[name="invoice"] form[name="legalEntity"] errors entry'
                    =>
                    'Не более 300 символов',
                ),
            ),
            /***********************************************************************************************
             * 'supplierInvoiceSku'
             ***********************************************************************************************/
            'valid supplierInvoiceSku' => array(
                201,
                array('supplierInvoiceSku' => 'supplierInvoiceSku'),
            ),
            'valid supplierInvoiceSku 100 chars' => array(
                201,
                array('supplierInvoiceSku' => str_repeat('z', 100)),
            ),
            'empty supplierInvoiceSku' => array(
                201,
                array('supplierInvoiceSku' => ''),
            ),
            'not valid supplierInvoiceSku too long' => array(
                400,
                array('supplierInvoiceSku' => str_repeat("z", 105)),
                array(
                    'form[name="invoice"] form[name="supplierInvoiceSku"] errors entry'
                    =>
                    'Не более 100 символов',
                ),
            ),
        );
    }
}
