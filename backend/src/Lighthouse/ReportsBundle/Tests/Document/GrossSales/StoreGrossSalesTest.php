<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossSales;

use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\ReportsBundle\Document\GrossSales\Store\GrossSalesStoreRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StoreGrossSalesTest extends WebTestCase
{
    public function testCalculateGrossSales()
    {
        $store = $this->factory()->store()->getStore('1');

        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

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
            ->flush();

        $this->getGrossSalesReportService()->recalculateStoreGrossSalesReport();

        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 07:00', null);
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 08:00', '603.53');
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 09:00', '603.53');
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 10:00', '298.68');
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 11:00', null);

        $this->getGrossSalesReportService()->recalculateStoreGrossSalesReport();

        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 07:00', null);
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 08:00', '603.53');
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 09:00', '603.53');
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 10:00', '298.68');
        $this->assertStoreGrossSalesDayHourReport($store->id, '-1 days 11:00', null);
    }

    /**
     * @param string $storeId
     * @param string  $dayHour
     * @param float|string|null $expectedHourSum
     */
    public function assertStoreGrossSalesDayHourReport($storeId, $dayHour, $expectedHourSum)
    {
        $reportForHour = $this->getReportRepository()->findOneByStoreIdAndDayHour($storeId, $dayHour);
        if (null === $expectedHourSum) {
            $this->assertNull($reportForHour);
        } else {
            $this->assertEquals($expectedHourSum, $reportForHour->hourSum->toString());
        }
    }

    /**
     * @return GrossSalesReportManager
     */
    protected function getGrossSalesReportService()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_sales.manager');
    }

    /**
     * @return GrossSalesStoreRepository
     */
    protected function getReportRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_sales.store.repository');
    }
}
