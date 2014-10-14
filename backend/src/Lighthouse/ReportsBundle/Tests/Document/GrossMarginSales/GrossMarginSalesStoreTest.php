<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMarginSales;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Store\GrossMarginSalesStoreRepository;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;

class GrossMarginSalesStoreTest extends WebTestCase
{
    /**
     * @return GrossMarginSalesReportManager
     */
    public function getGrossMarginSalesReportManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin_sales.manager');
    }

    /**
     * @return GrossMarginSalesStoreRepository
     */
    public function getGrossMarginSalesStoreRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_margin_sales.store.repository');
    }

    /**
     * @return GrossMarginManager
     */
    protected function getGrossMarginManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
    }

    public function testCalculate()
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $groups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $productId1 = $this->createProductByName('1.1', $groups['1']->id);
        $productId2 = $this->createProductByName('1.2', $groups['1']->id);
        $productId3 = $this->createProductByName('2.3', $groups['2']->id);

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '-7 days'), $stores['1']->id)
                ->createInvoiceProduct($productId1, 10, 20)
                ->createInvoiceProduct($productId2, 5, 25)
                ->createInvoiceProduct($productId3, 7, 14)
            ->persist()
                ->createInvoice(array('date' => '-7 days'), $stores['2']->id)
                ->createInvoiceProduct($productId1, 11, 21)
                ->createInvoiceProduct($productId2, 6, 26)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($stores['1'], '-1 days 11:23')
                ->createReceiptProduct($productId1, 1, 30)
                ->createReceiptProduct($productId2, 2.5, 31.09)
            ->persist()
                ->createSale($stores['2'], '-1 days 09:56')
                ->createReceiptProduct($productId1, 6, 36)
                ->createReceiptProduct($productId2, 3.5, 33.39)
            ->persist()
                ->createSale($stores['1'], '-2 days 19:13')
                ->createReceiptProduct($productId1, 2, 31)
                ->createReceiptProduct($productId2, 2.5, 31.09)
            ->persist()
                ->createSale($stores['2'], '-3 days 23:56')
                ->createReceiptProduct($productId1, 3, 29)
                ->createReceiptProduct($productId2, 1, 30.09)
            ->persist()
                ->createSale($stores['1'], '-5 days 00:11')
                ->createReceiptProduct($productId1, 1, 30)
                ->createReceiptProduct($productId2, 1, 30)
            ->flush();

        $trialBalanceCount = $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->assertEquals(5, $trialBalanceCount);

        $recalculateCount = $this->getGrossMarginSalesReportManager()->recalculateStoreReport();
        $this->assertEquals(5, $recalculateCount);

        $this->assertStoreReportNotFound($stores['1'], '00:00:00');
        $this->assertStoreReportNotFound($stores['2'], '00:00:00');

        $this->assertStoreReport($stores['1'], '-1 days 00:00:00', 107.73, 82.5, 25.23, 3.5);
        $this->assertStoreReport($stores['2'], '-1 days 00:00:00', 332.87, 217.00, 115.87, 9.5);

        $this->assertStoreReport($stores['1'], '-2 days 00:00:00', 139.73, 102.5, 37.23, 4.5);
        $this->assertStoreReportNotFound($stores['2'], '-2 days 00:00:00');

        $this->assertStoreReportNotFound($stores['1'], '-3 days 00:00:00');
        $this->assertStoreReport($stores['2'], '-3 days 00:00:00', 117.09, 89, 28.09, 4);

        $this->assertStoreReportNotFound($stores['2'], '-4 days 00:00:00');
        $this->assertStoreReportNotFound($stores['2'], '-4 days 00:00:00');

        $this->assertStoreReport($stores['1'], '-5 days 00:00:00', 60, 45, 15, 2);
        $this->assertStoreReportNotFound($stores['2'], '-5 days 00:00:00');
    }

    public function assertStoreReport(
        Store $store,
        $day,
        $grossSales,
        $costOfGoods,
        $grossMargin,
        $quantity
    ) {
        $day = new DateTimestamp($day);
        $report = $this->getGrossMarginSalesStoreRepository()->findByStoreIdAndDay($store->id, $day);

        $this->assertEquals($grossSales, $report->grossSales->toString());
        $this->assertEquals($costOfGoods, $report->costOfGoods->toString());
        $this->assertEquals($grossMargin, $report->grossMargin->toString());
        $this->assertEquals($quantity, $report->quantity->toString());
    }

    /**
     * @param Store $store
     * @param $day
     */
    public function assertStoreReportNotFound(Store $store, $day)
    {
        $day = new DateTimestamp($day);
        $report = $this->getGrossMarginSalesStoreRepository()->findByStoreIdAndDay($store->id, $day);

        $this->assertNull($report);
    }
}
