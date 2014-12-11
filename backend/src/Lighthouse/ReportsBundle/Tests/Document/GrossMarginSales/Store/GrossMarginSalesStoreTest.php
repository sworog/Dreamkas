<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMarginSales\Store;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Store\GrossMarginSalesStoreRepository;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;

class GrossMarginSalesStoreTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
        $this->authenticateProject();
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

        $product1 = $this->factory()->catalog()->getProduct('1.1', $groups['1']);
        $product2 = $this->factory()->catalog()->getProduct('1.2', $groups['1']);
        $product3 = $this->factory()->catalog()->getProduct('2.3', $groups['2']);

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '-7 days'), $stores['1']->id)
                ->createInvoiceProduct($product1->id, 10, 20)
                ->createInvoiceProduct($product2->id, 5, 25)
                ->createInvoiceProduct($product3->id, 7, 14)
            ->persist()
                ->createInvoice(array('date' => '-7 days'), $stores['2']->id)
                ->createInvoiceProduct($product1->id, 11, 21)
                ->createInvoiceProduct($product2->id, 6, 26)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($stores['1'], '-1 days 11:23')
                ->createReceiptProduct($product1->id, 1, 30)
                ->createReceiptProduct($product2->id, 2.5, 31.09)
            ->persist()
                ->createSale($stores['1'], '-1 days 19:30')
                ->createReceiptProduct($product1->id, 2, 30)
                ->createReceiptProduct($product3->id, 5, 15)
            ->persist()
                ->createSale($stores['2'], '-1 days 09:56')
                ->createReceiptProduct($product1->id, 6, 36)
                ->createReceiptProduct($product2->id, 3.5, 33.39)
            ->persist()
                ->createSale($stores['1'], '-2 days 19:13')
                ->createReceiptProduct($product1->id, 2, 31)
                ->createReceiptProduct($product2->id, 2.5, 31.09)
            ->persist()
                ->createSale($stores['2'], '-3 days 23:56')
                ->createReceiptProduct($product1->id, 3, 29)
                ->createReceiptProduct($product2->id, 1, 30.09)
            ->persist()
                ->createSale($stores['1'], '-5 days 00:11')
                ->createReceiptProduct($product1->id, 1, 30)
                ->createReceiptProduct($product2->id, 1, 30)
            ->flush();

        $trialBalanceCount = $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->assertEquals(5, $trialBalanceCount);


        $recalculateCount = $this->getGrossMarginSalesStoreRepository()->recalculate(null, 4);
        $this->assertEquals(5, $recalculateCount);

        $this->assertStoreReportNotFound($stores['1'], '00:00:00');
        $this->assertStoreReportNotFound($stores['2'], '00:00:00');

        $this->assertStoreReport($stores['1'], '-1 days 00:00:00', 242.73, 192.50, 50.23, 10.5);
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

        $this->assertNotNull($report, 'Store report not found');

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
