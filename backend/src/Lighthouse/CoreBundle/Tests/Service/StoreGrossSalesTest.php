<?php

namespace Lighthouse\CoreBundle\Tests\Service;

use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository;
use Lighthouse\CoreBundle\Service\StoreGrossSalesReportService;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StoreGrossSalesTest extends WebTestCase
{
    public function testCalculateGrossSales()
    {
        $storeId = $this->createStore('1');
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
        $this->assertEquals("0", $reportForHour->runningSum->toString());
        $this->assertEquals("0", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 8:00');
        $this->assertEquals("603.53", $reportForHour->runningSum->toString());
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 9:00');
        $this->assertEquals("1207.06", $reportForHour->runningSum->toString());
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 10:00');
        $this->assertEquals("1505.74", $reportForHour->runningSum->toString());
        $this->assertEquals("298.68", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 11:00');
        $this->assertEquals("1505.74", $reportForHour->runningSum->toString());
        $this->assertEquals("0", $reportForHour->hourSum->toString());



        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 7:00');
        $this->assertEquals("0", $reportForHour->runningSum->toString());
        $this->assertEquals("0", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 8:00');
        $this->assertEquals("603.53", $reportForHour->runningSum->toString());
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 9:00');
        $this->assertEquals("1207.06", $reportForHour->runningSum->toString());
        $this->assertEquals("603.53", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 10:00');
        $this->assertEquals("1505.74", $reportForHour->runningSum->toString());
        $this->assertEquals("298.68", $reportForHour->hourSum->toString());

        $reportForHour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 11:00');
        $this->assertEquals("1505.74", $reportForHour->runningSum->toString());
        $this->assertEquals("0", $reportForHour->hourSum->toString());
    }

    /**
     * @return StoreGrossSalesReportService
     */
    public function getGrossSalesReportService()
    {
        return $this->getContainer()->get('lighthouse.core.service.store.report.gross_sales');
    }

    /**
     * @return StoreGrossSalesRepository
     */
    public function getReportRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.store_gross_sales');
    }
}
