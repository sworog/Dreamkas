<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMarginSales\Product;

use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\IntegrationBundle\Test\WebTestCase;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;

class GrossMarginSalesProductTest extends WebTestCase
{
    /**
     * @return GrossMarginSalesProductRepository
     */
    public function getGrossMarginSalesProductRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_margin_sales.product.repository');
    }

    /**
     * @return GrossMarginManager
     */
    protected function getGrossMarginManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
    }

    /**
     * @dataProvider calculateGrossMarginSalesProductProvider
     * @param int $batch
     */
    public function testCalculateGrossMarginSalesProduct($batch)
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct(1);
        $productId2 = $this->createProduct(2);
        $productId3 = $this->createProduct(3);

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-7 day 10:00:00'))), $store->id)
                ->createInvoiceProduct($productId1, 7.77, 23.99)
                ->createInvoiceProduct($productId2, 3.33, 55)
                ->createInvoiceProduct($productId3, 13, 17)
            ->persist();

        $this->factory()
            ->receipt()
                ->createSale($store, '-1 day 12:34:55')
                ->createReceiptProduct($productId1, 3, 40.53)
                ->createReceiptProduct($productId3, 2.55, 25.77)
            ->persist()
                ->createSale($store, '-2 day 10:33:44')
                ->createReceiptProduct($productId2, 0.47, 100.13)
            ->persist()
                ->createSale($store, '-2 day 15:20:33')
                ->createReceiptProduct($productId2, 1.56, 99.7)
            ->persist()
                ->createSale($store, '-4 day 16:45:34')
                ->createReceiptProduct($productId3, 8, 26)
            ->flush();

        $trialBalanceCount = $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->assertEquals(3, $trialBalanceCount);

        $recalculateCount = $this->getGrossMarginSalesProductRepository()->recalculate(null, $batch);
        $this->assertEquals(4, $recalculateCount);

        $this->assertProductReport($store->id, $productId1, '-1 day 00:00:00', 121.59, 71.97, 49.62, 3);
        $this->assertProductReport($store->id, $productId3, '-1 day 00:00:00', 65.71, 43.35, 22.36, 2.55);
        $this->assertProductReport($store->id, $productId2, '-2 day 00:00:00', 202.59, 111.65, 90.94, 2.03);
        $this->assertProductReport($store->id, $productId3, '-4 day 00:00:00', 208, 136, 72, 8);
    }

    /**
     * @return array
     */
    public function calculateGrossMarginSalesProductProvider()
    {
        return array(
            'batch size 1' => array(1),
            'batch size 2' => array(2),
            'batch size 3' => array(3),
            'batch size 4' => array(4),
            'batch size 5' => array(5),
        );
    }

    public function assertProductReport(
        $storeId,
        $productId,
        $day,
        $grossSales,
        $costOfGoods,
        $grossMargin,
        $quantity
    ) {
        $date = new DateTimestamp($day);
        $report = $this->getGrossMarginSalesProductRepository()->findByStoreIdProductIdAndDay(
            $storeId,
            $productId,
            $date
        );

        $this->assertNotNull($report, sprintf('Product report for day %s not found', $day));

        $this->assertEquals($grossSales, $report->grossSales->toString());
        $this->assertEquals($costOfGoods, $report->costOfGoods->toString());
        $this->assertEquals($grossMargin, $report->grossMargin->toString());
        $this->assertEquals($quantity, $report->quantity->toString());
    }
}
