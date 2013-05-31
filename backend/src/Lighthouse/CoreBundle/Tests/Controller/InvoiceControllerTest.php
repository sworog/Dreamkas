<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
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
     * @dataProvider postInvoiceDataProvider
     */
    public function testPostInvoiceAction(array $invoiceData, array $assertions = array())
    {
        $crawler = $this->client->request(
            'POST',
            '/api/1/invoices.xml',
            $invoiceData
        );

        Assert::assertResponseCode(201, $this->client);

        $this->runCrawlerAssertions($crawler, $assertions, true);
    }

    /**
     * @dataProvider postInvoiceDataProvider
     */
    public function testGetInvoicesAction(array $invoiceData)
    {
        for ($i = 0; $i < 5; $i++) {
            $invoiceData['sku'] = '12122004' . $i;
            $this->createInvoice($invoiceData);
        }

        $crawler = $this->client->request(
            'GET',
            '/api/1/invoices.xml'
        );

        Assert::assertResponseCode(200, $this->client);

        $this->assertEquals(5, $crawler->filterXPath('//invoices/invoice')->count());
    }

    /**
     * @dataProvider postInvoiceDataProvider
     */
    public function testGetInvoice(array $invoiceData, array $assertions)
    {
        $id = $this->createInvoice($invoiceData);

        $crawler = $this->client->request(
            'GET',
            '/api/1/invoices/' . $id . '.xml'
        );

        Assert::assertResponseCode(200, $this->client);

        $this->runCrawlerAssertions($crawler, $assertions, true);
    }

    public function postInvoiceDataProvider()
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
                    'supplierInvoiceDate' => '17.03.2013'
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

    public function testGetInvoiceNotFound()
    {
        $id = 'not_exists_id';
        $this->client->request(
            'GET',
            '/api/1/invoices/' . $id . '.xml'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostInvoiceValidation($expectedCode, array $data, array $assertions = array())
    {
        $invoiceData = $this->postInvoiceDataProvider();

        $postData = $data + $invoiceData['invoice']['data'];

        $crawler = $this->client->request(
            'POST',
            '/api/1/invoices.xml',
            $postData
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->runCrawlerAssertions($crawler, $assertions);
    }

    /**
      * @dataProvider providerSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid
     */
    public function testPostInvoiceSupplierInvoiceDateIsValidAndAcceptanceDateIsInvalid(array $data)
    {
        $invoiceData = $this->postInvoiceDataProvider();

        $postData = $data + $invoiceData['invoice']['data'];

        $crawler = $this->client->request(
            'POST',
            '/api/1/invoices.xml',
            $postData
        );

        Assert::assertResponseCode(400, $this->client);

        $this->assertContains(
            'Вы ввели неверную дату',
            $crawler->filter('form[name="acceptanceDate"] errors entry')->first()->text()
        );
        $this->assertEmpty(
            $crawler->filter('form[name="supplierInvoiceDate"] errors entry')->count()
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
     * @dataProvider putInvoiceDataProvider
     */
    public function testPutInvoiceAction(
        array $postData,
        array $postAssertions,
        array $putData,
        $expectedCode,
        array $putAssertions
    ) {
        $postJson = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/invoices',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postJson);
        $invoiceId = $postJson['id'];
        foreach ($postAssertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $postJson);
        }

        $putData += $postData;
        $putJson = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/invoices/' . $invoiceId,
            $putData
        );

        Assert::assertResponseCode($expectedCode, $this->client);
        Assert::assertJsonPathEquals($invoiceId, 'id', $postJson);
        foreach ($putAssertions as $jsonPath => $expected) {
            Assert::assertJsonPathContains($expected, $jsonPath, $putJson);
        }
    }

    public function putInvoiceDataProvider()
    {
        $now = new \DateTime();
        $data = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013'
        );
        $assertions = array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18T12:56:00+0400',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '2013-03-17T00:00:00+0400',
            'createdDate' => $now->format('Y-m-d\TH:'),
        );

        return array(
            array(
                'postData' => $data,
                'postAssertions' => $assertions,
                'putData' => array(
                    'supplier' => 'ООО "Подставщик"',
                ),
                'expectedCode' => 200,
                'putAssertions' => array(
                    'supplier' => 'ООО "Подставщик"',
                ) + $assertions,
            ),
            array(
                'postData' => $data,
                'postAssertions' => $assertions,
                'putData' => array(
                    'supplierInvoiceDate' => '16.03.2013',
                ),
                'expectedCode' => 200,
                'putAssertions' => array(
                    'supplierInvoiceDate' => '2013-03-16T00:00:00+0400',
                ),
            ),
            array(
                'postData' => $data,
                'postAssertions' => $assertions,
                'putData' => array(
                    'supplierInvoiceDate' => '19.03.2013',
                ),
                'expectedCode' => 400,
                'putAssertions' => array(
                    'children.supplierInvoiceDate.errors.0' => 'Дата накладной не должна быть старше даты приемки',
                ),
            ),
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
                    'form[name="sku"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid sku too long' => array(
                400,
                array('sku' => str_repeat("z", 105)),
                array(
                    'form[name="sku"] errors entry'
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
                    'form[name="supplier"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid supplier too long' => array(
                400,
                array('supplier' => str_repeat("z", 305)),
                array(
                    'form[name="supplier"] errors entry'
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
                    'form[name="accepter"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid accepter too long' => array(
                400,
                array('accepter' => str_repeat("z", 105)),
                array(
                    'form[name="accepter"] errors entry'
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
                    'form[name="legalEntity"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid legalEntity too long' => array(
                400,
                array('legalEntity' => str_repeat("z", 305)),
                array(
                    'form[name="legalEntity"] errors entry'
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
                    'form[name="supplierInvoiceSku"] errors entry'
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
                    'form[name="acceptanceDate"] errors entry'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid acceptanceDate 2013-02-31' => array(
                400,
                array('acceptanceDate' => '2013-02-31'),
                array(
                    'form[name="acceptanceDate"] errors entry'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'not valid acceptanceDate aaa' => array(
                400,
                array('acceptanceDate' => 'aaa'),
                array(
                    'form[name="acceptanceDate"] errors entry'
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
                    'form[name="supplierInvoiceDate"] errors entry'
                    =>
                    'Вы ввели неверную дату',
                ),
            ),
            'not valid supplierInvoiceDate aaa' => array(
                400,
                array('supplierInvoiceDate' => 'aaa'),
                array(
                    'form[name="supplierInvoiceDate"] errors entry'
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
                    'form[name="supplierInvoiceDate"] errors entry'
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
                    'errors entry'
                    =>
                    'Эта форма не должна содержать дополнительных полей',
                ),
            ),
        );
    }
}
