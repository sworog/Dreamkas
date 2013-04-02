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
    public function testPostInvoiceAction(array $invoiceData, array $assertions = array())
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            'api/1/invoices',
            array('invoice' => $invoiceData)
        );

        $content = $client->getResponse()->getContent();
        $this->assertEquals(201, $client->getResponse()->getStatusCode(), $content);

        $this->runCrawlerAssertions($crawler, $assertions, true);
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

        $crawler = $client->request(
            'GET',
            'api/1/invoices'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(5, $crawler->filterXPath('//invoices/invoice')->count());
    }

    /**
     * @dataProvider invoiceDataProvider
     */
    public function testGetInvoice(array $invoiceData, array $assertions)
    {
        $client = static::createClient();

        $id = $this->createInvoice($invoiceData, $client);

        $crawler = $client->request(
            'GET',
            '/api/1/invoices/' . $id
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->runCrawlerAssertions($crawler, $assertions, true);
    }

    public function testGetInvoiceNotFound()
    {
        $client = static::createClient();

        $id = 'not_exists_id';
        $client->request(
            'GET',
            '/api/1/invoices/' . $id
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostInvoiceValidation($expectedCode, array $data, array $assertions = array())
    {
        $client = static::createClient();

        $invoiceData = $this->invoiceDataProvider();

        $postData = array_merge($invoiceData['invoice']['data'], $data);

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

        $this->runCrawlerAssertions($crawler, $assertions);
    }

    /**
      * @dataProvider providerSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid
     */
    public function testPostInvoiceSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid(array $data)
    {
        $client = static::createClient();

        $invoiceData = $this->invoiceDataProvider();

        $postData = $data + $invoiceData['invoice']['data'];

        $crawler = $client->request(
            'POST',
            'api/1/invoices',
            array('invoice' => $postData)
        );

        $this->assertEquals(
            400,
            $client->getResponse()->getStatusCode(),
            $client->getResponse()->getContent()
        );

        $this->assertContains(
            'Вы ввели неверную дату',
            $crawler->filter('form[name="invoice"] form[name="acceptanceDate"] errors entry')->first()->text()
        );
        $this->assertEmpty(
            $crawler->filter('form[name="invoice"] form[name="supplierInvoiceDate"] errors entry')->count()
        );
    }

    /**
     * @return array
     */
    public function providerSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid()
    {
        return array(
            'supplierInvoiceDate in past' => array(
                array(
                    'acceptanceDate' => 'aaa',
                    'supplierInvoiceDate' => '2012-03-14'
                ),
            ),
            'supplierInvoiceDate in future' => array(
                array(
                    'acceptanceDate' => 'aaa',
                    'supplierInvoiceDate' => '2015-03-14'
                ),
            )
        );
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

    public function invoiceDataProvider()
    {
        $now = new \DateTime();
        return array(
            'invoice' => array(
                'data' => array(
                    'sku' => 'sdfwfsf232',
                    'supplier' => 'ООО "Поставщик"',
                    'acceptanceDate' => '2013-03-18 12:56',
                    'accepter' => 'Приемных Н.П.',
                    'legalEntity' => 'ООО "Магазин"',
                    'supplierInvoiceSku' => '1248373',
                    'supplierInvoiceDate' => '17.03.2013',
                    'sumTotal' => 1000,
                ),
                // Assertions xpath
                'assertions' => array(
                    '//invoice/sku' => 'sdfwfsf232',
                    '//invoice/supplier' => 'ООО "Поставщик"',
                    '//invoice/acceptanceDate' => '2013-03-18T12:56:00+0400',
                    '//invoice/accepter' => 'Приемных Н.П.',
                    '//invoice/legalEntity' => 'ООО "Магазин"',
                    '//invoice/supplierInvoiceSku' => '1248373',
                    '//invoice/supplierInvoiceDate' => '2013-03-17T00:00:00+0400',
                    '//invoice/createdDate' => $now->format('Y-m-d\TH:'),
                )
            )
        );
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
            /***********************************************************************************************
             * 'acceptanceDate'
             ***********************************************************************************************/
            'valid acceptanceDate 2013-03-26T12:34:56' => array(
                201,
                array('acceptanceDate' => '2013-03-26T12:34:56'),
                array("acceptanceDate" => '2013-03-26T12:34:56+0400')
            ),
            'valid acceptanceDate 2013-03-26' => array(
                201,
                array('acceptanceDate' => '2013-03-26'),
                array("acceptanceDate" => '2013-03-26T00:00:00+0400')
            ),
            'valid acceptanceDate 2013-03-26 12:34' => array(
                201,
                array('acceptanceDate' => '2013-03-26 12:34'),
                array("acceptanceDate" => '2013-03-26T12:34:00+0400')
            ),
            'valid acceptanceDate 2013-03-26 12:34:45' => array(
                201,
                array('acceptanceDate' => '2013-03-26 12:34:45'),
                array("acceptanceDate" => '2013-03-26T12:34:45+0400')
            ),
            'empty acceptanceDate' => array(
                400,
                array('acceptanceDate' => ''),
                array(
                    'form[name="invoice"] form[name="acceptanceDate"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid acceptanceDate 2013-02-31' => array(
                400,
                array('acceptanceDate' => '2013-02-31'),
                array(
                    'form[name="invoice"] form[name="acceptanceDate"] errors entry'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'not valid acceptanceDate aaa' => array(
                400,
                array('acceptanceDate' => 'aaa'),
                array(
                    'form[name="invoice"] form[name="acceptanceDate"] errors entry'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            /***********************************************************************************************
             * 'supplierInvoiceDate'
             ***********************************************************************************************/
            'valid supplierInvoiceDate 2013-03-16T12:34:56' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16T12:34:56'),
                array("supplierInvoiceDate" => '2013-03-16T12:34:56+0400')
            ),
            'valid supplierInvoiceDate 2013-03-16' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16'),
                array("supplierInvoiceDate" => '2013-03-16T00:00:00+0400')
            ),
            'valid supplierInvoiceDate 2013-03-16 12:34' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16 12:34'),
                array("supplierInvoiceDate" => '2013-03-16T12:34:00+0400')
            ),
            'valid supplierInvoiceDate 2013-03-16 12:34:45' => array(
                201,
                array('supplierInvoiceDate' => '2013-03-16 12:34:45'),
                array("supplierInvoiceDate" => '2013-03-16T12:34:45+0400')
            ),
            'empty supplierInvoiceDate' => array(
                201,
                array('supplierInvoiceDate' => ''),
            ),
            'not valid supplierInvoiceDate 2013-02-31' => array(
                400,
                array('supplierInvoiceDate' => '2013-02-31'),
                array(
                    'form[name="invoice"] form[name="supplierInvoiceDate"] errors entry'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'not valid supplierInvoiceDate aaa' => array(
                400,
                array('supplierInvoiceDate' => 'aaa'),
                array(
                    'form[name="invoice"] form[name="supplierInvoiceDate"] errors entry'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'valid supplierInvoiceDate is less than acceptanceDate' => array(
                201,
                array(
                    'supplierInvoiceDate' => '2013-03-14',
                    'acceptanceDate' => '2013-03-15'
                )
            ),
            'not valid supplierInvoiceDate is more than acceptanceDate' => array(
                400,
                array(
                    'supplierInvoiceDate' => '2013-03-15',
                    'acceptanceDate' => '2013-03-14'
                ),
                array(
                    'form[name="invoice"] form[name="supplierInvoiceDate"] errors entry'
                    =>
                    'Дата накладной не должна быть старше даты приемки',
                ),
            ),
            /***********************************************************************************************
             * 'createdDate'
             ***********************************************************************************************/
            'not valid createdDate' => array(
                400,
                array('createdDate' => '2013-03-26T12:34:56'),
                array(
                    'form[name="invoice"] > errors entry'
                    =>
                    'Эта форма не должна содержать дополнительных полей',
                ),
            ),
        );
    }
}
