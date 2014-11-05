<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMarginSales\Network;

use Lighthouse\CoreBundle\Test\DataAwareTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Network\GrossMarginSalesNetworkRepository;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;

class GrossMarginSalesNetworkTest extends DataAwareTestCase
{
    /**
     * @return GrossMarginSalesNetworkRepository
     */
    public function getGrossMarginSalesNetworkRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_margin_sales.network.repository');
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
                ->createSale($stores['1'], '-1 days 19:30')
                ->createReceiptProduct($productId1, 2, 30)
                ->createReceiptProduct($productId3, 5, 15)
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

        $recalculateCount = $this->getGrossMarginSalesNetworkRepository()->recalculate();
        $this->assertEquals(4, $recalculateCount);

        $this->assertNetworkReportNotFound('today');

        $this->assertNetworkReport('-1 days', 575.60, 409.50, 166.10, 20);
        $this->assertNetworkReport('-2 days', 139.73, 102.5, 37.23, 4.5);
        $this->assertNetworkReport('-3 days', 117.09, 89, 28.09, 4);
        $this->assertNetworkReportNotFound('-4 days');
        $this->assertNetworkReport('-5 days', 60, 45, 15, 2);
    }

    /**
     * @param string $day
     * @param float $grossSales
     * @param float $costOfGoods
     * @param float $grossMargin
     * @param float $quantity
     */
    public function assertNetworkReport($day, $grossSales, $costOfGoods, $grossMargin, $quantity)
    {
        $day = new DateTimestamp($day);
        $day->setTime(0, 0, 0);
        $report = $this->getGrossMarginSalesNetworkRepository()->findByDay($day);

        $this->assertNotNull($report, 'Network report not found');

        $this->assertEquals($grossSales, $report->grossSales->toString());
        $this->assertEquals($costOfGoods, $report->costOfGoods->toString());
        $this->assertEquals($grossMargin, $report->grossMargin->toString());
        $this->assertEquals($quantity, $report->quantity->toString());
    }

    /**
     * @param string $day
     */
    public function assertNetworkReportNotFound($day)
    {
        $day = new DateTimestamp($day);
        $day->setTime(0, 0, 0);
        $report = $this->getGrossMarginSalesNetworkRepository()->findByDay($day);

        $this->assertNull($report);
    }
}
