<?php

namespace Lighthouse\CoreBundle\Tests\Integration\OneC\Import\Invoices;

use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Integration\OneC\Import\Invoices\InvoicesImporter;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;

class InvoicesImporterTest extends WebTestCase
{
    public function testImport()
    {
        $storeId1 = $this->factory->createStore(1, 'Магазин Галерея');
        $storeId2 = $this->factory->createStore(2, 'СитиМолл');
        $storeId3 = $this->factory->createStore(3, 'ТК Невский 104');
        $storeId4 = $this->factory->createStore(4, 'ТК НОРД 1-44');
        $storeId5 = $this->factory->createStore(5, 'ТК Пик');

        $this->createProductsBySku(
            array(
                'ЦБ000003386',
                'ЦБ000003388',
                'ЦБ000003389',
                'ЦБ000003390',
                'ЦБ000003391',
                'ЦБ000003392',
                'ЦБ000003393',
                'ЦБ000003331',
                'ЦБ000003395',
                'ЦБ000003396',
                'ЦБ000003397',
                'ЦБ000003398',
                'ЦБ000003375',
                'ЦБ000003382',
                'ЦБ000003475',
                'ЦБ000003304',
                'ЦБ000003328',
                'ЦБ000003253',
                'ЦБ000003611',
                'ЦБ000003612',
                'ЦБ000002333',
                'ЦБ000003613',
                'ЦБ000001512',
                'ЦБ000003614',
                'ЦБ000003615',
                'ЦБ000000698',
                'ЦБ000003616',
                'ЦБ000003617',
                'ЦБ000003619',
                'ЦБ000001520',
                'ЦБ000003620',
                'ЦБ000000648',
                'ЦБ000003621',
                'ЦБ000003595',
                'ЦБ000003622',
                'ЦБ000000160',
                'ЦБ000001829',
                'ЦБ000003623',
                'ЦБ000001300',
                'ЦБ000000329',
                'ЦБ000002305',
                'ЦБ000003624',
                'ЦБ000003625',
                'ЦБ000003626',
                'ЦБ000002217',
                'ЦБ000003627',
                'ЦБ000003628',
                'ЦБ000003629',
                'ЦБ000001053',
                'ЦБ000002647',
                'ЦБ000003630',
                'ЦБ000003631',
                'ЦБ000003632',
                'ЦБ000001566',
                'ЦБ000003633',
                'ЦБ000003634',
                'ЦБ000003635',
                'ЦБ000001768',
                'ЦБ000003636',
                'ЦБ000003637',
                'ЦБ000000371',
                'ЦБ000003638',
                'ЦБ000000855',
                'ЦБ000003639',
                'ЦБ000000853',
                '00000000156',
                'ЦБ000003640',
                'ЦБ000001238',
                'ЦБ000003641',
                'ЦБ000003642',
                'ЦБ000003643',
                'ЦБ000003644',
                'ЦБ000002538',
                'ЦБ000002503',
                'ЦБ000002888',
                'ЦБ000002955',
                'ЦБ000002193',
                '00000000392',
                'ЦБ000002837',
                'ЦБ000002911',
                'ЦБ000002426',
                'ЦБ000002424',
                'ЦБ000002712',
                'ЦБ000002950',
                'ЦБ000003471',
                'ЦБ000002971',
                'ЦБ000002909',
                'ЦБ000002957',
                'ЦБ000002951',
                'ЦБ000003878',
                'ЦБ000003879',
            )
        );

        $filePath = $this->getFixtureFilePath('Integration/OneC/Import/Invoices/amn.csv');

        /* @var InvoicesImporter $importer */
        $importer = $this->getContainer()->get('lighthouse.core.integration.onec.import.invoices.importer');
        $output = new TestOutput();
        $importer->import($filePath, 5, $output);

        /* @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getContainer()->get('lighthouse.core.document.repository.invoice');

        $storeInvoices = array(
            $storeId1 => 4,
            $storeId2 => 4,
            $storeId3 => 5,
            $storeId4 => 1,
            $storeId5 => 1
        );
        foreach ($storeInvoices as $storeId => $count) {
            $invoices = $invoiceRepository->findBy(array('store' => $storeId));
            $this->assertEquals($count, $invoices->count());
        }

        $storeInvoiceProducts = array(
            $storeId1 => 20,
            $storeId2 => 17,
            $storeId3 => 89,
            $storeId4 => 8,
            $storeId5 => 3
        );

        /* @var InvoiceProductRepository $invoiceProductRepository */
        $invoiceProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.invoice_product');
        foreach ($storeInvoiceProducts as $storeId => $count) {
            $invoiceProducts = $invoiceProductRepository->findBy(array('store' => $storeId));
            $this->assertEquals($count, $invoiceProducts->count());
        }
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Store with address
     */
    public function testImportStoreNotFound()
    {
        $filePath = $this->getFixtureFilePath('Integration/OneC/Import/Invoices/amn.csv');

        /* @var InvoicesImporter $importer */
        $importer = $this->getContainer()->get('lighthouse.core.integration.onec.import.invoices.importer');
        $output = new TestOutput();
        $importer->import($filePath, 5, $output);
    }


    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Product with sku
     */
    public function testImportProductNotFound()
    {
        $this->factory->createStore(1, 'Магазин Галерея');

        $filePath = $this->getFixtureFilePath('Integration/OneC/Import/Invoices/amn.csv');

        /* @var InvoicesImporter $importer */
        $importer = $this->getContainer()->get('lighthouse.core.integration.onec.import.invoices.importer');
        $output = new TestOutput();
        $importer->import($filePath, 5, $output);
    }
}
