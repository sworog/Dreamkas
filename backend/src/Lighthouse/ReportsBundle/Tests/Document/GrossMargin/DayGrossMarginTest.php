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
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();


        $sale1 = $this->factory()->createSale($store1->id, '2014-01-09 12:23:12', 1750);
        $this->factory()->createSaleProduct(250, 7, $productId, $sale1);  // CoG: 800

        $sale2 = $this->factory()->createSale($store1->id, '2014-01-09 16:23:12', 500);
        $this->factory()->createSaleProduct(250, 2, $productId, $sale2);  // CoG: 300

        $sale3 = $this->factory()->createSale($store1->id, '2014-01-10 12:23:12', 1500);
        $this->factory()->createSaleProduct(250, 6, $productId, $sale3);  // CoG: 1150

        $sale4 = $this->factory()->createSale($store2->id, '2014-01-09 12:30:12', 2100);
        $this->factory()->createSaleProduct(300, 7, $productId, $sale4);  // CoG: 800

        $sale5 = $this->factory()->createSale($store2->id, '2014-01-09 16:30:12', 600);
        $this->factory()->createSaleProduct(300, 2, $productId, $sale5);  // CoG: 300

        $sale6 = $this->factory()->createSale($store2->id, '2014-01-10 12:30:12', 1800);
        $this->factory()->createSaleProduct(300, 6, $productId, $sale6);  // CoG: 1150
        $this->factory()->flush();


        $this->processJobs();
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
