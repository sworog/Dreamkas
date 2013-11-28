<?php

namespace Lighthouse\CoreBundle\Tests\Service;

use Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository;
use Lighthouse\CoreBundle\Service\StoreGrossSalesReport;
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
                'createDate' => '-1 days 8:01',
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
                'createDate' => "-1 days 9:01",
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
                'createDate' => "-1 days 10:01",
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

        $storeGrossSalesReportService = $this->getGrossSalesReportService();

        $storeGrossSalesReportService->recalculateGrossSales();

        $reportRepository = $this->getReportRepository();

        $reportFor7Hour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 7:00');
        $this->assertEquals("0", $reportFor7Hour->value->toString());

        $reportFor8Hour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 8:00');
        $this->assertEquals("603.53", $reportFor8Hour->value->toString());

        $reportFor9Hour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 9:00');
        $this->assertEquals("1207.06", $reportFor9Hour->value->toString());

        $reportFor10Hour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 10:00');
        $this->assertEquals("1810.59", $reportFor10Hour->value->toString());

        $reportFor11Hour = $reportRepository->findOneByStoreIdAndDayHour($storeId, '-1 days 11:00');
        $this->assertEquals("1810.59", $reportFor11Hour->value->toString());
    }

    /**
     * @return StoreGrossSalesReport
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
