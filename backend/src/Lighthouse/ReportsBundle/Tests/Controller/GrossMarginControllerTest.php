<?php

namespace Lighthouse\ReportsBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsManager;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use DateTime;

class GrossMarginControllerTest extends WebTestCase
{
    protected function prepareData()
    {
        $store = $this->factory->getStore();

        $product1 = $this->createProduct("1");
        $product2 = $this->createProduct("2");
        $product3 = $this->createProduct("3");

        $invoice1 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-01 12:23:12'), $store);
        $this->createInvoiceProduct($invoice1, $product1, 5, 100, $store);
        $this->createInvoiceProduct($invoice1, $product2, 10, 201, $store);
        $this->createInvoiceProduct($invoice1, $product3, 5, 120, $store);

        $invoice2 = $this->createInvoice(array('sku' => 2, 'acceptanceDate' => '2014-01-02 12:23:12'), $store);
        $this->createInvoiceProduct($invoice2, $product1, 5, 101, $store);
        $this->createInvoiceProduct($invoice2, $product2, 5, 200, $store);
        $this->createInvoiceProduct($invoice2, $product3, 10, 130, $store);

        $invoice3 = $this->createInvoice(array('sku' => 3, 'acceptanceDate' => '2014-01-03 12:23:12'), $store);
        $this->createInvoiceProduct($invoice3, $product1, 5, 102, $store);
        $this->createInvoiceProduct($invoice3, $product2, 5, 205, $store);
        $this->createInvoiceProduct($invoice3, $product3, 10, 135, $store);

        $invoice4 = $this->createInvoice(array('sku' => 4, 'acceptanceDate' => '2014-01-04 12:23:12'), $store);
        $this->createInvoiceProduct($invoice4, $product1, 20, 101, $store);
        $this->createInvoiceProduct($invoice4, $product2, 5, 200, $store);
        $this->createInvoiceProduct($invoice4, $product3, 10, 130, $store);

        $invoice5 = $this->createInvoice(array('sku' => 5, 'acceptanceDate' => '2014-01-05 12:23:12'), $store);
        $this->createInvoiceProduct($invoice5, $product1, 5, 101, $store);
        $this->createInvoiceProduct($invoice5, $product2, 5, 200, $store);
        $this->createInvoiceProduct($invoice5, $product3, 10, 130, $store);

        $invoice6 = $this->createInvoice(array('sku' => 6, 'acceptanceDate' => '2014-01-06 12:23:12'), $store);
        $this->createInvoiceProduct($invoice6, $product1, 5, 101, $store);
        $this->createInvoiceProduct($invoice6, $product2, 5, 200, $store);
        $this->createInvoiceProduct($invoice6, $product3, 10, 130, $store);


        $sale1 = $this->factory->createSale($store, "2014-01-02 12:23:12", 222);
        $this->factory->createSaleProduct(150, 3, $product1, $sale1);  // 450 - (3 x 100 = 300) = 150
        $this->factory->createSaleProduct(250, 3, $product2, $sale1);  // 750 - (3 x 201 = 603) = 147
        $this->factory->createSaleProduct(150, 3, $product3, $sale1);  // 450 - (3 x 120 = 360) = 90
        $sale2 = $this->factory->createSale($store, "2014-01-03 12:23:12", 222);
        $this->factory->createSaleProduct(150, 3, $product1, $sale2);  // 450 - (2 x 100 + 1 x 101 = 301) = 149
        $this->factory->createSaleProduct(250, 8, $product2, $sale2);  // 2000 - (7 x 201 + 1 x 200 = 1607) = 393
        $this->factory->createSaleProduct(150, 3, $product3, $sale2);  // 450 - (2 x 120 + 1 x 130 = 370) = 80
        $sale4 = $this->factory->createSale($store, "2014-01-05 12:23:12", 222);
        $this->factory->createSaleProduct(150, 8, $product1, $sale4);  // 1200 - (4 x 101 + 4 x 102 = 812) = 388
        $this->factory->createSaleProduct(250, 3, $product2, $sale4);  // 750 - (3 x 200 = 600) = 150
        $this->factory->createSaleProduct(150, 6, $product3, $sale4);  // 900 - (6 x 130 = 780) = 120
        $sale5 = $this->factory->createSale($store, "2014-01-06 12:23:12", 222);
        $this->factory->createSaleProduct(150, 5, $product1, $sale5);  // 750 - (1 x 102 + 4 x 101 = 506) = 244
        $this->factory->createSaleProduct(250, 6, $product2, $sale5);  // 1500 - (1 x 200 + 5 x 205 = 1225) = 275
        $this->factory->createSaleProduct(150, 2, $product3, $sale5);  // 300 - (2 x 130 = 260) = 40
        $sale6 = $this->factory->createSale($store, "2014-01-07 12:23:12", 222);
        $this->factory->createSaleProduct(150, 6, $product1, $sale6);  // 900 - (6 x 101 = 606) = 294
        $this->factory->createSaleProduct(250, 3, $product2, $sale6);  // 750 - (3 x 200 = 600) = 150
        $this->factory->createSaleProduct(150, 15, $product3, $sale6); // 2250 - (1x130 + 10x135 + 4x130 = 2000) = 250
        $sale7 = $this->factory->createSale($store, "2014-01-08 12:23:12", 222);
        $this->factory->createSaleProduct(150, 8, $product1, $sale7);  // 1200 - (8 x 101 = 808) = 392
        $this->factory->createSaleProduct(250, 3, $product2, $sale7);  // 750 - (3 x 200 = 600) = 150
        $this->factory->createSaleProduct(150, 10, $product3, $sale7); // 1500 - (10 x 130 = 1300) = 200
        $sale8 = $this->factory->createSale($store, "2014-01-09 12:23:12", 222);
        $this->factory->createSaleProduct(150, 3, $product1, $sale8);  // 450 - (3 x 101 = 303) = 147
        $this->factory->createSaleProduct(250, 7, $product2, $sale8);  // 1750 - (7 x 200 = 1400) = 350
        $this->factory->createSaleProduct(150, 1, $product3, $sale8);  // 150 - (1 x 130 = 130) = 20

        $this->factory->flush();

        return $store;
    }

    /**
     * @return GrossMarginManager
     */
    protected function getGrossMarginManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
    }

    public function testGetStoreGrossMarginReports()
    {
        $storeId = $this->prepareData();

        // Calculate CostOfGoods
        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();

        $accessToken = $this->factory->authAsStoreManager($storeId);

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/stores/" . $storeId . "/reports/grossMargin",
            null,
            array('time' => date('c', strtotime("2014-01-10 10:35:47")))
        );

        $expectedResponse = array(
            array(
                'date' => '2014-01-09T00:00:00+0400',
                'sum' => 517,
            ),
            array(
                'date' => '2014-01-08T00:00:00+0400',
                'sum' => 742,
            ),
            array(
                'date' => '2014-01-07T00:00:00+0400',
                'sum' => 694,
            ),
            array(
                'date' => '2014-01-06T00:00:00+0400',
                'sum' => 559,
            ),
            array(
                'date' => '2014-01-05T00:00:00+0400',
                'sum' => 658,
            ),
            array(
                'date' => '2014-01-04T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-03T00:00:00+0400',
                'sum' => 622,
            ),
            array(
                'date' => '2014-01-02T00:00:00+0400',
                'sum' => 387,
            ),
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetStoreGrossMarginReportsWithMissingDaysAtTheBeginning()
    {
        $storeId = $this->prepareData();

        // Calculate CostOfGoods
        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $accessToken = $this->factory->authAsStoreManager($storeId);

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/stores/" . $storeId . "/reports/grossMargin",
            null,
            array('time' => date('c', strtotime("2014-01-12 10:35:47")))
        );

        $expectedResponse = array(
            array(
                'date' => '2014-01-11T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-10T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-09T00:00:00+0400',
                'sum' => 517,
            ),
            array(
                'date' => '2014-01-08T00:00:00+0400',
                'sum' => 742,
            ),
            array(
                'date' => '2014-01-07T00:00:00+0400',
                'sum' => 694,
            ),
            array(
                'date' => '2014-01-06T00:00:00+0400',
                'sum' => 559,
            ),
            array(
                'date' => '2014-01-05T00:00:00+0400',
                'sum' => 658,
            ),
            array(
                'date' => '2014-01-04T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-03T00:00:00+0400',
                'sum' => 622,
            ),
            array(
                'date' => '2014-01-02T00:00:00+0400',
                'sum' => 387,
            ),
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetStoreGrossMarginReportsWithDataFromAutotests()
    {
        $store = $this->factory->getStore('1');
        $product = $this->createProduct('1');

        $date = new DateTimestamp();

        $invoice1 = $this->createInvoice(
            array(
                'sku' => 1,
                'acceptanceDate' => $date->copy()->modify('-2 days 08:00')->format(DateTime::ISO8601)
            ),
            $store
        );
        $this->createInvoiceProduct($invoice1, $product, 50, 90, $store);

        $invoice2 = $this->createInvoice(
            array(
                'sku' => 2,
                'acceptanceDate' => $date->copy()->modify('-1 day 08:00')->format(DateTime::ISO8601)
            ),
            $store
        );
        $this->createInvoiceProduct($invoice2, $product, 35, 100, $store);

        $sale1 = $this->factory->createSale(
            $store,
            $date->copy()->modify('-2 days 10:00'),
            3125
        );
        $this->factory->createSaleProduct(125, 25, $product, $sale1);

        $sale2 = $this->factory->createSale(
            $store,
            $date->copy()->modify('-1 day 10:00'),
            3600
        );
        $this->factory->createSaleProduct(120, 30, $product, $sale2);
        $this->factory->flush();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $accessToken = $this->factory->authAsStoreManager($store);

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/stores/" . $store . "/reports/grossMargin"
        );

        $expectedResponse = array(
            array(
                'date' => $date->copy()->modify('-1 day 00:00')->format(DateTime::ISO8601),
                'sum' => 850,
            ),
            array(
                'date' => $date->copy()->modify('-2 days 00:00')->format(DateTime::ISO8601),
                'sum' => 875,
            ),
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetStoreGrossMarginReportsSimpleExampleOnBoard()
    {
        $store = $this->factory->getStore("1");
        $accessToken = $this->factory->authAsStoreManager($store);
        $product = $this->createProduct("1");

        // Begin inventory
        $invoice1 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-01 12:23:12'), $store);
        $this->createInvoiceProduct($invoice1, $product, 3, 100, $store);
        $this->createInvoiceProduct($invoice1, $product, 1, 101, $store);
        $this->createInvoiceProduct($invoice1, $product, 2, 102, $store);


        // Inventory purchase
        $invoice2 = $this->createInvoice(array('sku' => 2, 'acceptanceDate' => '2014-01-02 12:23:12'), $store);
        $this->createInvoiceProduct($invoice2, $product, 1, 101, $store);
        $this->createInvoiceProduct($invoice2, $product, 2, 102, $store);


        // Sale
        $sale1 = $this->factory->createSale($store, "2014-01-02 12:23:12", 1050);
        $this->factory->createSaleProduct(150, 7, $product, $sale1);
        $this->factory->flush();


        // Calculate CostOfGoods
        /* @var \Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();
        /* @var GrossMarginManager $grossMarginReportManager */
        $grossMarginReportManager = $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
        $grossMarginReportManager->recalculateStoreGrossMargin();

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/stores/" . $store . "/reports/grossMargin",
            null,
            array('time' => date('c', strtotime("2014-01-03 10:35:47")))
        );

        $expectedResponse = array(
            array(
                'date' => '2014-01-02T00:00:00+0400',
                'sum' => 344.00,
            )
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetStoreGrossMarginReportsWithDataFromBoardTwo()
    {
        $store = $this->factory->getStore("1");
        $accessToken = $this->factory->authAsStoreManager($store);
        $product = $this->createProduct("1");

        // Begin inventory
        $invoice1 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-01 12:23:12'), $store);
        $this->createInvoiceProduct($invoice1, $product, 3, 1, $store);
        $this->createInvoiceProduct($invoice1, $product, 2, 2, $store);

        // Day one
        $invoice1 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-02 12:23:12'), $store);
        $this->createInvoiceProduct($invoice1, $product, 3, 3, $store);

        $sale1 = $this->factory->createSale($store, "2014-01-02 12:23:12", 233);
        $this->factory->createSaleProduct(5, 4, $product, $sale1);
        $this->factory->flush();

        // Day two
        $invoice1 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-03 12:23:12'), $store);
        $this->createInvoiceProduct($invoice1, $product, 2, 2, $store);

        $sale1 = $this->factory->createSale($store, "2014-01-03 12:23:12", 233);
        $this->factory->createSaleProduct(5, 3, $product, $sale1);
        $this->factory->flush();


        // Calculate CostOfGoods
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();
        /* @var GrossMarginManager $grossMarginReportManager */
        $grossMarginReportManager = $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
        $grossMarginReportManager->recalculateStoreGrossMargin();


        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/stores/" . $store . "/reports/grossMargin",
            null,
            array('time' => date('c', strtotime("2014-01-04 10:35:47")))
        );

        $expectedResponse = array(
            array(
                'date' => '2014-01-03T00:00:00+0400',
                'sum' => 7,
            ),
            array(
                'date' => '2014-01-02T00:00:00+0400',
                'sum' => 15,
            ),
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetStoreGrossMarginReportsWithMissingDaysAtTheBeginningCalculateOnJobs()
    {
        $this->clearJobs();

        $storeId = $this->prepareData();

        // Calculate CostOfGoods
        /* @var CostOfGoodsManager $costOfGoodsManager */
        $costOfGoodsManager = $this
            ->getContainer()
            ->get('lighthouse.core.document.trial_balance.cost_of_goods.manager');
        $costOfGoodsManager->createCalculateJobsForUnprocessed();

        /* @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('lighthouse.core.job.manager');
        $jobManager->startWatchingTubes();
        while (1) {
            $job = $jobManager->reserveJob(0);
            if (null == $job) {
                break;
            }

            $jobManager->processJob($job);
        }
        $jobManager->stopWatchingTubes();


        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $accessToken = $this->factory->authAsStoreManager($storeId);

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/stores/" . $storeId . "/reports/grossMargin",
            null,
            array('time' => date('c', strtotime("2014-01-12 10:35:47")))
        );

        $expectedResponse = array(
            array(
                'date' => '2014-01-11T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-10T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-09T00:00:00+0400',
                'sum' => 517,
            ),
            array(
                'date' => '2014-01-08T00:00:00+0400',
                'sum' => 742,
            ),
            array(
                'date' => '2014-01-07T00:00:00+0400',
                'sum' => 694,
            ),
            array(
                'date' => '2014-01-06T00:00:00+0400',
                'sum' => 559,
            ),
            array(
                'date' => '2014-01-05T00:00:00+0400',
                'sum' => 658,
            ),
            array(
                'date' => '2014-01-04T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-03T00:00:00+0400',
                'sum' => 622,
            ),
            array(
                'date' => '2014-01-02T00:00:00+0400',
                'sum' => 387,
            ),
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
