<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMargin\Store;

use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\ReportsBundle\Document\GrossMargin\Store\StoreDayGrossMarginRepository;

class StoreDayGrossMarginTest extends WebTestCase
{
    public function prepareData()
    {
        $store = $this->factory()->store()->getStore('1');
        $productId = $this->createProduct('1');
        $storeProductId = $this->factory()->getStoreProduct($store->id, $productId);

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-10 12:23:13'), $store->id)
                ->createInvoiceProduct($productId, 16.36, 10.09)
            ->persist()
                ->createInvoice(array('date' => '2014-01-13 09:11:41'), $store->id)
                ->createInvoiceProduct($productId, 20.501, 10.54)
                ->createInvoiceProduct($productId, 10, 10.54)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store, '2014-01-11 00:45:09')
                ->createReceiptProduct($productId, 9.102, 12.11)
                ->createReceiptProduct($productId, 1, 12.11)
            ->persist()
                ->createSale($store, '2014-01-11 15:34:19')
                ->createReceiptProduct($productId, 2.675, 12.11)
            ->persist()
                ->createSale($store, '2014-01-11 23:45:09')
                ->createReceiptProduct($productId, 0.502, 12.19)
                ->createReceiptProduct($productId, 1, 12.19)
            ->persist()
                ->createSale($store, '2014-01-12 15:45:09')
                ->createReceiptProduct($productId, 2.056, 11.99)
            ->persist()
                ->createSale($store, '2014-01-14 00:00:10')
                ->createReceiptProduct($productId, 4.56, 11.49)
            ->persist()
                ->createSale($store, '2014-01-14 04:02:10')
                ->createReceiptProduct($productId, 1.76, 11.49)
            ->flush();

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
