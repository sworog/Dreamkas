<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossSales;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\ReportsBundle\Document\GrossSales\Product\GrossSalesProductRepository;

class ProductGrossSalesTest extends ContainerAwareTestCase
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
        $this->clearMongoDb();
        $this->authenticateProject();

        $store = $this->factory()->store()->getStore('1');
        $otherStore = $this->factory()->store()->getStore('Other');

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $storeProductId1 = $this->factory()->getStoreProduct($store->id, $products['1']->id);
        $storeProductId2 = $this->factory()->getStoreProduct($store->id, $products['2']->id);
        $storeProductId3 = $this->factory()->getStoreProduct($store->id, $products['3']->id);

        $this->factory()
            ->receipt()
                ->createSale($store, '-1 days 8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 9:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 10:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
            ->persist()
                ->createSale($otherStore, '-1 days 8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
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
