<?php

namespace Lighthouse\CoreBundle\Tests\Integration\OneC\Import\Invoices;

use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository;
use Lighthouse\CoreBundle\Integration\OneC\Import\Invoices\InvoicesImporter;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;

class InvoicesImporterTest extends WebTestCase
{
    /**
     * @param string $filePath
     * @param int $batchSize
     * @return TestOutput
     */
    protected function import($filePath, $batchSize = 5)
    {
        /* @var InvoicesImporter $importer */
        $importer = $this->getContainer()->get('lighthouse.core.integration.onec.import.invoices.importer');
        $output = new TestOutput();
        $importer->import($filePath, $batchSize, $output);

        return $output;
    }

    /**
     * @param array $storeInvoiceCount
     */
    protected function assertStoreInvoiceCount(array $storeInvoiceCount)
    {
        /* @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getContainer()->get('lighthouse.core.document.repository.invoice');
        foreach ($storeInvoiceCount as $storeId => $count) {
            $invoices = $invoiceRepository->findBy(array('store' => $storeId));
            $this->assertEquals($count, $invoices->count());
        }
    }

    /**
     * @param array $storeInvoiceProductCount
     */
    protected function assertStoreInvoiceProductCount(array $storeInvoiceProductCount)
    {
        /* @var InvoiceProductRepository $invoiceProductRepository */
        $invoiceProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.invoice_product');
        foreach ($storeInvoiceProductCount as $storeId => $count) {
            $invoiceProducts = $invoiceProductRepository->findBy(array('store' => $storeId));
            $this->assertEquals($count, $invoiceProducts->count());
        }
    }

    public function testImport()
    {
        $this->markTestSkipped();
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
        $this->import($filePath);

        $storeInvoiceCount = array(
            $storeId1 => 4,
            $storeId2 => 4,
            $storeId3 => 5,
            $storeId4 => 1,
            $storeId5 => 1
        );
        $this->assertStoreInvoiceCount($storeInvoiceCount);

        $storeInvoiceProductCount = array(
            $storeId1 => 20,
            $storeId2 => 17,
            $storeId3 => 89,
            $storeId4 => 8,
            $storeId5 => 3
        );
        $this->assertStoreInvoiceProductCount($storeInvoiceProductCount);
    }

    /**
     * @expectedExceptionMessage Store with address
     */
    public function testImportStoreNotFound()
    {
        $filePath = $this->getFixtureFilePath('Integration/OneC/Import/Invoices/amn.csv');

        $output = $this->import($filePath);

        $display = $output->getDisplay();
        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Store with address 'Магазин Галерея' not found",
            $display
        );
        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Store with address 'СитиМолл' not found",
            $display
        );
        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Store with address 'ТК Невский 104' not found",
            $display
        );
        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Store with address 'ТК НОРД 1-44' not found",
            $display
        );
        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Store with address 'ТК Пик' not found",
            $display
        );
    }

    public function testImportProductNotFound()
    {
        $this->markTestSkipped();
        $this->factory->createStore(1, 'Магазин Галерея');

        $this->createProductsBySku(
            array(
                'ЦБ000003386',
            )
        );

        $filePath = $this->getFixtureFilePath('Integration/OneC/Import/Invoices/amn.csv');

        $output = $this->import($filePath);

        $display = $output->getDisplay();

        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Product with sku 'ЦБ000003395' not found",
            $display
        );
        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Product with sku 'ЦБ000003382' not found",
            $display
        );
        $this->assertContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Product with sku 'ЦБ000003328' not found",
            $display
        );

        $this->assertNotContains(
            "[Lighthouse\\CoreBundle\\Exception\\RuntimeException] Product with sku 'ЦБ000003386' not found",
            $display
        );
    }

    public function testImportNoStoreInInvoiceRow()
    {
        $storeId1 = $this->factory->createStore(1, 'Авиаконструкторов 2');
        $storeId2 = $this->factory->createStore(2, 'Есенина 1');
        $storeId3 = $this->factory->createStore(3, 'Металлистов, 116 (МЕ)');

        $this->createProductsBySku(
            array(
                'Ц0000001371',
                'Ц0000001313',
                'Ц0000001852',
                'Ц0000001417',
                'Ц0000000235',
                'ЕС000000107',
                'РТ000000035',
                'МЕ000000036',
                'АВ000000221',
                'Ц0000001937',
                'АВ000000259',
                'МЕ000000364',
                'МЕ000000365',
                'ЕС000000197',
                'ЕС000000221',
                'Ц0000002019',
                'АВ000000297',
                'МЕ000000084',
                'МЕ000000086',
                'МЕ000000085',
                'Ц0000001637',
                'Ц0000001640',
            )
        );

        $filePath = $this->getFixtureFilePath('Integration/OneC/Import/Invoices/food.csv');

        $this->import($filePath, 100);

        $storeInvoiceCount = array(
            $storeId1 => 2,
            $storeId2 => 2,
            $storeId3 => 3,
        );
        $this->assertStoreInvoiceCount($storeInvoiceCount);

        $storeInvoiceProductCount = array(
            $storeId1 => 7,
            $storeId2 => 4,
            $storeId3 => 11,
        );
        $this->assertStoreInvoiceProductCount($storeInvoiceProductCount);
    }
}
