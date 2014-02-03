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

        $store = $this->factory->getStore("1");
        $store2 = $this->factory->getStore("2");
        $product = $this->createProduct("1");


        $invoice1 = $this->createInvoice(array('sku' => '1', 'acceptanceDate' => '2014-01-01 12:56'), $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 100, $store);
        $invoice2 = $this->createInvoice(array('sku' => '2', 'acceptanceDate' => '2014-01-02 12:56'), $store);
        $this->createInvoiceProduct($invoice2, $product, 5, 150, $store);
        $invoice3 = $this->createInvoice(array('sku' => '3', 'acceptanceDate' => '2014-01-03 12:56'), $store);
        $this->createInvoiceProduct($invoice3, $product, 10, 200, $store);

        $invoice4 = $this->createInvoice(array('sku' => '4', 'acceptanceDate' => '2014-01-01 12:00'), $store2);
        $this->createInvoiceProduct($invoice4, $product, 5, 100, $store2);
        $invoice5 = $this->createInvoice(array('sku' => '5', 'acceptanceDate' => '2014-01-02 12:00'), $store2);
        $this->createInvoiceProduct($invoice5, $product, 5, 150, $store2);
        $invoice6 = $this->createInvoice(array('sku' => '6', 'acceptanceDate' => '2014-01-03 12:00'), $store2);
        $this->createInvoiceProduct($invoice6, $product, 10, 200, $store2);


        $sale1 = $this->factory->createSale($store, "2014-01-09 12:23:12", 1750);
        $this->factory->createSaleProduct(250, 7, $product, $sale1);  // CoG: 800

        $sale2 = $this->factory->createSale($store, "2014-01-09 16:23:12", 500);
        $this->factory->createSaleProduct(250, 2, $product, $sale2);  // CoG: 300

        $sale3 = $this->factory->createSale($store, "2014-01-10 12:23:12", 1500);
        $this->factory->createSaleProduct(250, 6, $product, $sale3);  // CoG: 1150

        $sale4 = $this->factory->createSale($store2, "2014-01-09 12:30:12", 2100);
        $this->factory->createSaleProduct(300, 7, $product, $sale4);  // CoG: 800

        $sale5 = $this->factory->createSale($store2, "2014-01-09 16:30:12", 600);
        $this->factory->createSaleProduct(300, 2, $product, $sale5);  // CoG: 300

        $sale6 = $this->factory->createSale($store2, "2014-01-10 12:30:12", 1800);
        $this->factory->createSaleProduct(300, 6, $product, $sale6);  // CoG: 1150
        $this->factory->flush();


        $costOfGoodsCalculator->calculateUnprocessed();
        $dayGrossMarginRepository->recalculate();


        $report = $dayGrossMarginRepository->findOneByDate(new DateTimestamp("2014-01-09"));
        $this->assertNotNull($report);
        $this->assertEquals(2750, $report->sum->toNumber());

        $report = $dayGrossMarginRepository->findOneByDate(new DateTimestamp("2014-01-10"));
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
