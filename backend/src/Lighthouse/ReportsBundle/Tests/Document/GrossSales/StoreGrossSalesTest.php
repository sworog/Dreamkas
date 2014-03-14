<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossSales;

use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\ReportsBundle\Document\GrossSales\Store\GrossSalesStoreRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StoreGrossSalesTest extends WebTestCase
{
    public function testCalculateGrossSales()
    {
        $storeId = $this->factory->store()->getStore('1');
        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $sales = array(
            array(
                'storeId' => $storeId,
                'createdDate' => '-1 days 8:01',
                'sumTotal' => 603.53,
                'positions' => array(
                    array(
                        'productId' => $product1Id,
                        'quantity' => 3,
                        'price' => 34.77
                    ),
                    array(
                        'productId' => $product2Id,
                        'quantity' => 3,
                        'price' => 64.79
                    ),
                    array(
                        'productId' => $product3Id,
                        'quantity' => 7,
                        'price' => 43.55,
                    ),
                ),
            ),
            array(
                'storeId' => $storeId,
                'createdDate' => "-1 days 9:01",
                'sumTotal' => 603.53,
                'positions' => array(
                    array(
                        'productId' => $product1Id,
                        'quantity' => 3,
                        'price' => 34.77
                    ),
                    array(
                        'productId' => $product2Id,
                        'quantity' => 3,
                        'price' => 64.79
                    ),
                    array(
                        'productId' => $product3Id,
                        'quantity' => 7,
                        'price' => 43.55,
                    ),
                ),
            ),

            array(
                'storeId' => $storeId,
                'createdDate' => "-1 days 10:01",
                'sumTotal' => 298.68,
                'positions' => array(
                    array(
                        'productId' => $product1Id,
                        'quantity' => 3,
                        'price' => 34.77
                    ),
                    array(
                        'productId' => $product2Id,
                        'quantity' => 3,
                        'price' => 64.79
                    ),
                ),
            ),
        );

        $this->factory->createSales($sales);

        $storeGrossSalesReportService = $this->getGrossSalesReportService();

        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $reportRepository = $this->getReportRepository();

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 7:00');
        $this->assertNull($reportForHour);

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 8:00');
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 9:00');
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 10:00');
        $this->assertEquals("298.68", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 11:00');
        $this->assertNull($reportForHour);



        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 7:00');
        $this->assertNull($reportForHour);

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 8:00');
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 9:00');
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 10:00');
        $this->assertEquals("298.68", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 11:00');
        $this->assertNull($reportForHour);
    }

    /**
     * @return GrossSalesReportManager
     */
    public function getGrossSalesReportService()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_sales.manager');
    }

    /**
     * @return GrossSalesStoreRepository::
     */
    public function getReportRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_sales.store.repository');
    }
}
