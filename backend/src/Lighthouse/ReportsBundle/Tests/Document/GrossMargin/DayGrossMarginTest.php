<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMargin;

use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Document\GrossMargin\DayGrossMarginRepository;

class DayGrossMarginTest extends WebTestCase
{
    public function testDayGrossMargin()
    {
        $costOfGoodsCalculator = $this->getCostOfGoodsCalculator();
        $dayGrossMarginRepository = $this->getDayGrossMarginRepository();

        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $productId = $this->createProduct('1');

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-01 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->persist()
                ->createInvoice(array('date' => '2014-01-02 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->persist()
                ->createInvoice(array('date' => '2014-01-03 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->persist()
                ->createInvoice(array('date' => '2014-01-01 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->persist()
                ->createInvoice(array('date' => '2014-01-02 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->persist()
                ->createInvoice(array('date' => '2014-01-03 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();


        $this->factory()
            ->receipt()
                ->createSale($store1, '2014-01-09 12:23:12')
                ->createReceiptProduct($productId, 7, 250)
            ->persist()
                ->createSale($store1, '2014-01-09 16:23:12')
                ->createReceiptProduct($productId, 2, 250)  // CoG: 300
            ->persist()
                ->createSale($store1, '2014-01-10 12:23:12')
                ->createReceiptProduct($productId, 6, 250)  // CoG: 1150
            ->persist()
                ->createSale($store2, '2014-01-09 12:30:12')
                ->createReceiptProduct($productId, 7, 300)  // CoG: 800
            ->persist()
                ->createSale($store2, '2014-01-09 16:30:12')
                ->createReceiptProduct($productId, 2, 300)  // CoG: 300
            ->persist()
                ->createSale($store2, '2014-01-10 12:30:12')
                ->createReceiptProduct($productId, 6, 300)  // CoG: 1150
            ->flush();


        $costOfGoodsCalculator->calculateUnprocessed();
        $dayGrossMarginRepository->recalculate();


        $report = $dayGrossMarginRepository->findOneByDate(new DateTimestamp('2014-01-09'));
        $this->assertNotNull($report);
        $this->assertEquals(2750, $report->sum->toNumber());

        $report = $dayGrossMarginRepository->findOneByDate(new DateTimestamp('2014-01-10'));
        $this->assertNotNull($report);
        $this->assertEquals(1000, $report->sum->toNumber());
    }

    /**
     * @return DayGrossMarginRepository
     */
    protected function getDayGrossMarginRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_margin.repository');
    }

    /**
     * @return CostOfGoodsCalculator
     */
    protected function getCostOfGoodsCalculator()
    {
        return $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
    }
}
