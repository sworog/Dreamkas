<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossSales;

use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\ReportsBundle\Document\GrossSales\Product\GrossSalesProductRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ProductGrossSalesTest extends WebTestCase
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
        $storeId = $this->createStore('1');
        $storeOtherId = $this->createStore('Other');
        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');
        $storeProduct1Id = $this->factory->getStoreProduct($storeId, $product1Id);
        $storeProduct2Id = $this->factory->getStoreProduct($storeId, $product2Id);
        $storeProduct3Id = $this->factory->getStoreProduct($storeId, $product3Id);


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

            array(
                'storeId' => $storeOtherId,
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
        );

        $this->factory->createSales($sales);

        $grossSalesReportManager = $this->getGrossSalesReportManager();
        $grossSalesReportManager->recalculateGrossSalesProductReport();

        $productGrossSalesRepository = $this->getProductGrossSalesRepository();

        /**
         * Product 1
         */
        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct1Id, '-1 day 7:00:00');
        $this->assertEquals(0, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct1Id, '-1 day 8:00:00');
        $this->assertEquals(104.31, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct1Id, '-1 day 9:00:00');
        $this->assertEquals(104.31, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct1Id, '-1 day 10:00:00');
        $this->assertEquals(104.31, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct1Id, '-1 day 11:00:00');
        $this->assertEquals(0, $report->hourSum->toString());

        /**
         * Product 2
         */
        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct2Id, '-1 day 7:00:00');
        $this->assertEquals(0, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct2Id, '-1 day 8:00:00');
        $this->assertEquals(194.37, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct2Id, '-1 day 9:00:00');
        $this->assertEquals(194.37, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct2Id, '-1 day 10:00:00');
        $this->assertEquals(194.37, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct2Id, '-1 day 11:00:00');
        $this->assertEquals(0, $report->hourSum->toString());

        /**
         * Product 3
         */
        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct3Id, '-1 day 7:00:00');
        $this->assertEquals(0, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct3Id, '-1 day 8:00:00');
        $this->assertEquals(304.85, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct3Id, '-1 day 9:00:00');
        $this->assertEquals(304.85, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct3Id, '-1 day 10:00:00');
        $this->assertEquals(0, $report->hourSum->toString());

        $report = $productGrossSalesRepository->findByStoreProductAndDayHour($storeProduct3Id, '-1 day 11:00:00');
        $this->assertEquals(0, $report->hourSum->toString());
    }
}
