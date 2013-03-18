<?php

namespace Lighthouse\CoreBundle\Tests\Entity;

use Lighthouse\CoreBundle\Document\Invoice;

class InvoiceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $invoice = new Invoice();
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\Invoice', $invoice);
    }

    /**
     * @dataProvider invoiceDataProvider
     */
    public function testGetSetProperties(array $invoiceData)
    {
        $invoice = new Invoice();

        foreach ($invoiceData as $key => $value) {
            $invoice->$key = $value;
            $this->assertEquals($value, $invoice->$key);
        }

        $this->assertNull($invoice->id);
    }

    /**
     * @dataProvider invoiceDataProvider
     */
    public function testPopulateAndToArray(array $data)
    {
        $invoice = new Invoice();
        $invoice->populate($data);

        $invoiceArray = $invoice->toArray();
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $invoiceArray[$key]);
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
}
