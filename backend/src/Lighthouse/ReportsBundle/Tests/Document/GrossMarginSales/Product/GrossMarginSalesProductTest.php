<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMarginSales\Product;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\DataAwareTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;

class GrossMarginSalesProductTest extends DataAwareTestCase
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

    protected function prepareData()
    {
        $store = $this->factory()->store()->getStore();

        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-7 day 10:00:00'))), $store->id)
                ->createInvoiceProduct($productIds['1'], 7.77, 23.99)
                ->createInvoiceProduct($productIds['2'], 3.33, 55)
                ->createInvoiceProduct($productIds['3'], 13, 17)
            ->persist();

        $this->factory()
            ->receipt()
                ->createSale($store, '-1 day 12:34:55')
                ->createReceiptProduct($productIds['1'], 3, 40.53)
                ->createReceiptProduct($productIds['3'], 2.55, 25.77)
            ->persist()
                ->createSale($store, '-2 day 10:33:44')
                ->createReceiptProduct($productIds['2'], 0.47, 100.13)
            ->persist()
                ->createSale($store, '-2 day 15:20:33')
                ->createReceiptProduct($productIds['2'], 1.56, 99.7)
            ->persist()
                ->createSale($store, '-4 day 16:45:34')
                ->createReceiptProduct($productIds['3'], 8, 26)
            ->flush();

        return array($store, $productIds);
    }

    public function testCalculate()
    {
        list($store, $productIds) = $this->prepareData();

        $trialBalanceCount = $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->assertEquals(3, $trialBalanceCount);

        $recalculateCount = $this->getGrossMarginSalesProductRepository()->recalculate();
        $this->assertEquals(4, $recalculateCount);

        $this->assertProductReportValues($store, $productIds);
    }

    /**
     * @dataProvider CalculateWithBatchSizeProvider
     * @param int $batch
     */
    public function testCalculateWithBatchSize($batch)
    {
        list($store, $productIds) = $this->prepareData();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesProductRepository()->recalculate(null, $batch);

        $this->assertProductReportValues($store, $productIds);
    }

    public function testDoubleCalculateDoNotDoubleValues()
    {
        $this->prepareData();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesProductRepository()->recalculate();

        $count1 = $this->getGrossMarginSalesProductRepository()->count();
        $this->assertEquals(4, $count1);

        $this->getGrossMarginSalesProductRepository()->recalculate();

        $count2 = $this->getGrossMarginSalesProductRepository()->count();
        $this->assertEquals(4, $count2);
    }

    /**
     * @return array
     */
    public function calculateWithBatchSizeProvider()
    {
        return array(
            'batch size 1' => array(1),
            'batch size 2' => array(2),
            'batch size 3' => array(3),
            'batch size 4' => array(4),
            'batch size 5' => array(5),
        );
    }

    /**
     * @param Store $store
     * @param array $productIds
     */
    public function assertProductReportValues(Store $store, array $productIds)
    {
        $this->assertProductReport($store->id, $productIds['1'], '-1 day 00:00:00', 121.59, 71.97, 49.62, 3);
        $this->assertProductReport($store->id, $productIds['3'], '-1 day 00:00:00', 65.71, 43.35, 22.36, 2.55);
        $this->assertProductReport($store->id, $productIds['2'], '-2 day 00:00:00', 202.59, 111.65, 90.94, 2.03);
        $this->assertProductReport($store->id, $productIds['3'], '-4 day 00:00:00', 208, 136, 72, 8);
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param string $day
     * @param float $grossSales
     * @param float $costOfGoods
     * @param float $grossMargin
     * @param float $quantity
     */
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
        $report = $this->getGrossMarginSalesProductRepository()->findOneByStoreIdProductIdAndDay(
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
