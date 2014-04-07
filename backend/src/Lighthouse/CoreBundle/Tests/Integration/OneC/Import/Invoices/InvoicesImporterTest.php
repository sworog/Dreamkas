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

    public function testImportNoStoreInInvoiceRow()
    {
        $storeId1 = $this->factory->store()->getStoreId(1, 'Авиаконструкторов 2');
        $storeId2 = $this->factory->store()->getStoreId(2, 'Есенина 1');
        $storeId3 = $this->factory->store()->getStoreId(3, 'Металлистов, 116 (МЕ)');

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
