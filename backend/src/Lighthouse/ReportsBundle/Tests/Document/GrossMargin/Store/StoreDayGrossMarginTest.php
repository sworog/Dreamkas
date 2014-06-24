<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMargin\Store;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginRepository;

class StoreDayGrossMarginTest extends WebTestCase
{
    public function prepareData()
    {
        $storeId = $this->factory()->store()->getStoreId('1');
        $productId = $this->createProduct('1');
        $storeProductId = $this->factory()->getStoreProduct($storeId, $productId);

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-10 12:23:13'), $storeId)
                ->createInvoiceProduct($productId, 16.36, 10.09, $storeId)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-13 09:11:41'), $storeId)
                ->createInvoiceProduct($productId, 20.501, 10.54, $storeId)
                ->createInvoiceProduct($productId, 10, 10.54, $storeId)
            ->flush();

        $sale1 = $this->factory()->createSale($storeId, '2014-01-11 00:45:09', 110.23);
        $this->factory()->createSaleProduct(12.11, 9.102, $productId, $sale1);
        $this->factory()->createSaleProduct(12.11, 1, $productId, $sale1);
        $this->factory()->flush();

        $sale2 = $this->factory()->createSale($storeId, '2014-01-11 15:34:19', 110.23);
        $this->factory()->createSaleProduct(12.11, 2.675, $productId, $sale2);
        $this->factory()->flush();

        $sale3 = $this->factory()->createSale($storeId, '2014-01-11 23:45:09', 110.23);
        $this->factory()->createSaleProduct(12.19, 0.502, $productId, $sale3);
        $this->factory()->createSaleProduct(12.19, 1, $productId, $sale3);
        $this->factory()->flush();

        $sale4 = $this->factory()->createSale($storeId, '2014-01-12 15:45:09', 110.23);
        $this->factory()->createSaleProduct(11.99, 2.056, $productId, $sale4);
        $this->factory()->flush();

        $sale5 = $this->factory()->createSale($storeId, '2014-01-14 00:00:10', 110.23);
        $this->factory()->createSaleProduct(11.49, 4.56, $productId, $sale5);
        $this->factory()->flush();

        $sale6 = $this->factory()->createSale($storeId, '2014-01-14 04:02:10', 110.23);
        $this->factory()->createSaleProduct(11.49, 1.76, $productId, $sale6);
        $this->factory()->flush();

        $this->processJobs();

        return $storeProductId;
    }

    /**
     * @return \Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator
     */
    public function getCostOfGoodsCalculator()
    {
        return $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
    }

    /**
     * @return StoreDayGrossMarginRepository
     */
    public function getStoreDayGrossMarginRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_margin.store.repository');
    }

    public function testAggregateByDay()
    {
        $this->prepareData();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $result = $this->getStoreDayGrossMarginRepository()->aggregateByDay();
        $this->assertCount(3, $result);
    }

    public function testRecalculate()
    {
        $this->prepareData();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $result = $this->getStoreDayGrossMarginRepository()->recalculate();
        $this->assertEquals(3, $result);
    }
}
