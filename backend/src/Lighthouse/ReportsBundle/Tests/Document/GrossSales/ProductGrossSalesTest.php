<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossSales;

use Lighthouse\CoreBundle\Test\DataAwareTestCase;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\ReportsBundle\Document\GrossSales\Product\GrossSalesProductRepository;

class ProductGrossSalesTest extends DataAwareTestCase
{
    /**
     * @return GrossSalesReportManager
     */
    public function getGrossSalesReportManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_sales.manager');
    }

    /**
     * @return GrossSalesProductRepository
     */
    public function getProductGrossSalesRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_sales.product.repository');
    }

    public function testCalculateProductGrossSalesHourSumCalculate()
    {
        $store = $this->factory()->store()->getStore('1');
        $otherStore = $this->factory()->store()->getStore('Other');

        $productId1 = $this->createProductByName('1');
        $productId2 = $this->createProductByName('2');
        $productId3 = $this->createProductByName('3');

        $storeProductId1 = $this->factory()->getStoreProduct($store->id, $productId1);
        $storeProductId2 = $this->factory()->getStoreProduct($store->id, $productId2);
        $storeProductId3 = $this->factory()->getStoreProduct($store->id, $productId3);

        $this->factory()
            ->receipt()
                ->createSale($store, '-1 days 8:01')
                ->createReceiptProduct($productId1, 3, 34.77)
                ->createReceiptProduct($productId2, 3, 64.79)
                ->createReceiptProduct($productId3, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 9:01')
                ->createReceiptProduct($productId1, 3, 34.77)
                ->createReceiptProduct($productId2, 3, 64.79)
                ->createReceiptProduct($productId3, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 10:01')
                ->createReceiptProduct($productId1, 3, 34.77)
                ->createReceiptProduct($productId2, 3, 64.79)
            ->persist()
                ->createSale($otherStore, '-1 days 8:01')
                ->createReceiptProduct($productId1, 3, 34.77)
                ->createReceiptProduct($productId2, 3, 64.79)
                ->createReceiptProduct($productId3, 7, 43.55)
            ->flush();

        $this->getGrossSalesReportManager()->recalculateGrossSalesProductReport();

        // Product 1
        $this->assertStoreProductGrossSalesHourSum($storeProductId1, '-1 day 07:00:00', 0, 0);
        $this->assertStoreProductGrossSalesHourSum($storeProductId1, '-1 day 08:00:00', 104.31, 3);
        $this->assertStoreProductGrossSalesHourSum($storeProductId1, '-1 day 09:00:00', 104.31, 3);
        $this->assertStoreProductGrossSalesHourSum($storeProductId1, '-1 day 10:00:00', 104.31, 3);
        $this->assertStoreProductGrossSalesHourSum($storeProductId1, '-1 day 11:00:00', 0, 0);

        // Product 2
        $this->assertStoreProductGrossSalesHourSum($storeProductId2, '-1 day 07:00:00', 0, 0);
        $this->assertStoreProductGrossSalesHourSum($storeProductId2, '-1 day 08:00:00', 194.37, 3);
        $this->assertStoreProductGrossSalesHourSum($storeProductId2, '-1 day 09:00:00', 194.37, 3);
        $this->assertStoreProductGrossSalesHourSum($storeProductId2, '-1 day 10:00:00', 194.37, 3);
        $this->assertStoreProductGrossSalesHourSum($storeProductId2, '-1 day 11:00:00', 0, 0);

        // Product 3
        $this->assertStoreProductGrossSalesHourSum($storeProductId3, '-1 day 07:00:00', 0, 0);
        $this->assertStoreProductGrossSalesHourSum($storeProductId3, '-1 day 08:00:00', 304.85, 7);
        $this->assertStoreProductGrossSalesHourSum($storeProductId3, '-1 day 09:00:00', 304.85, 7);
        $this->assertStoreProductGrossSalesHourSum($storeProductId3, '-1 day 10:00:00', 0, 0);
        $this->assertStoreProductGrossSalesHourSum($storeProductId3, '-1 day 11:00:00', 0, 0);
    }

    /**
     * @param string $storeProductId
     * @param string $dayHour
     * @param float $expectedHourSum
     * @param float $expectedHourQuantity
     */
    public function assertStoreProductGrossSalesHourSum(
        $storeProductId,
        $dayHour,
        $expectedHourSum,
        $expectedHourQuantity
    ) {
        $report = $this->getProductGrossSalesRepository()->findByStoreProductAndDayHour($storeProductId, $dayHour);
        $this->assertEquals($expectedHourSum, $report->hourSum->toString());
        $this->assertEquals($expectedHourQuantity, $report->hourQuantity->toString());
    }
}
