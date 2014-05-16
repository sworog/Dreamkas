<?php

namespace Lighthouse\ReportsBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use DateTime;

class GrossMarginControllerTest extends WebTestCase
{
    protected function prepareData()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 100)
                ->createInvoiceProduct($productId2, 10, 201)
                ->createInvoiceProduct($productId3, 5, 120)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 102)
                ->createInvoiceProduct($productId2, 5, 205)
                ->createInvoiceProduct($productId3, 10, 135)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-04 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 20, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-05 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-06 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();


        $sale1 = $this->factory->createSale($store->id, "2014-01-02 12:23:12", 222);
        $this->factory->createSaleProduct(150, 3, $productId1, $sale1);  // 450 - (3 x 100 = 300) = 150
        $this->factory->createSaleProduct(250, 3, $productId2, $sale1);  // 750 - (3 x 201 = 603) = 147
        $this->factory->createSaleProduct(150, 3, $productId3, $sale1);  // 450 - (3 x 120 = 360) = 90
        $sale2 = $this->factory->createSale($store->id, "2014-01-03 12:23:12", 222);
        $this->factory->createSaleProduct(150, 3, $productId1, $sale2);  // 450 - (2 x 100 + 1 x 101 = 301) = 149
        $this->factory->createSaleProduct(250, 8, $productId2, $sale2);  // 2000 - (7 x 201 + 1 x 200 = 1607) = 393
        $this->factory->createSaleProduct(150, 3, $productId3, $sale2);  // 450 - (2 x 120 + 1 x 130 = 370) = 80
        $sale4 = $this->factory->createSale($store->id, "2014-01-05 12:23:12", 222);
        $this->factory->createSaleProduct(150, 8, $productId1, $sale4);  // 1200 - (4 x 101 + 4 x 102 = 812) = 388
        $this->factory->createSaleProduct(250, 3, $productId2, $sale4);  // 750 - (3 x 200 = 600) = 150
        $this->factory->createSaleProduct(150, 6, $productId3, $sale4);  // 900 - (6 x 130 = 780) = 120
        $sale5 = $this->factory->createSale($store->id, "2014-01-06 12:23:12", 222);
        $this->factory->createSaleProduct(150, 5, $productId1, $sale5);  // 750 - (1 x 102 + 4 x 101 = 506) = 244
        $this->factory->createSaleProduct(250, 6, $productId2, $sale5);  // 1500 - (1 x 200 + 5 x 205 = 1225) = 275
        $this->factory->createSaleProduct(150, 2, $productId3, $sale5);  // 300 - (2 x 130 = 260) = 40
        $sale6 = $this->factory->createSale($store->id, "2014-01-07 12:23:12", 222);
        $this->factory->createSaleProduct(150, 6, $productId1, $sale6);  // 900 - (6 x 101 = 606) = 294
        $this->factory->createSaleProduct(250, 3, $productId2, $sale6);  // 750 - (3 x 200 = 600) = 150
        $this->factory->createSaleProduct(150, 15, $productId3, $sale6); // 2250 - (1x130 + 10x135 + 4x130 = 2000) = 250
        $sale7 = $this->factory->createSale($store->id, "2014-01-08 12:23:12", 222);
        $this->factory->createSaleProduct(150, 8, $productId1, $sale7);  // 1200 - (8 x 101 = 808) = 392
        $this->factory->createSaleProduct(250, 3, $productId2, $sale7);  // 750 - (3 x 200 = 600) = 150
        $this->factory->createSaleProduct(150, 10, $productId3, $sale7); // 1500 - (10 x 130 = 1300) = 200
        $sale8 = $this->factory->createSale($store->id, "2014-01-09 12:23:12", 222);
        $this->factory->createSaleProduct(150, 3, $productId1, $sale8);  // 450 - (3 x 101 = 303) = 147
        $this->factory->createSaleProduct(250, 7, $productId2, $sale8);  // 1750 - (7 x 200 = 1400) = 350
        $this->factory->createSaleProduct(150, 1, $productId3, $sale8);  // 150 - (1 x 130 = 130) = 20

        $this->factory->flush();

        return $store->id;
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

        $accessToken = $this->factory->oauth()->authAsStoreManager($storeId);

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

        $accessToken = $this->factory->oauth()->authAsStoreManager($storeId);

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
        $store = $this->factory()->store()->getStore('1');
        $product = $this->createProduct('1');

        $date = new DateTimestamp();

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array('acceptanceDate' => $date->copy()->modify('-2 days 08:00')->format(DateTime::ISO8601)),
                    $store->id
                )
                ->createInvoiceProduct($product, 50, 90)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array('acceptanceDate' => $date->copy()->modify('-1 day 08:00')->format(DateTime::ISO8601)),
                    $store->id
                )
                ->createInvoiceProduct($product, 35, 100)
            ->flush();

        $sale1 = $this->factory()->createSale(
            $store->id,
            $date->copy()->modify('-2 days 10:00'),
            3125
        );
        $this->factory()->createSaleProduct(125, 25, $product, $sale1);

        $sale2 = $this->factory()->createSale(
            $store->id,
            $date->copy()->modify('-1 day 10:00'),
            3600
        );
        $this->factory()->createSaleProduct(120, 30, $product, $sale2);
        $this->factory()->flush();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/reports/grossMargin'
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
        $store = $this->factory()->store()->getStore('1');
        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $product = $this->createProduct('1');

        // Begin inventory
        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 3, 100)
                ->createInvoiceProduct($product, 1, 101)
                ->createInvoiceProduct($product, 2, 102)
            ->flush();


        // Inventory purchase
        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 1, 101)
                ->createInvoiceProduct($product, 2, 102)
            ->flush();


        // Sale
        $sale1 = $this->factory()->createSale($store->id, '2014-01-02 12:23:12', 1050);
        $this->factory()->createSaleProduct(150, 7, $product, $sale1);
        $this->factory()->flush();


        // Calculate CostOfGoods
        /* @var \Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();
        /* @var GrossMarginManager $grossMarginReportManager */
        $grossMarginReportManager = $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
        $grossMarginReportManager->recalculateStoreGrossMargin();

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $store->id . '/reports/grossMargin',
            null,
            array('time' => date('c', strtotime('2014-01-03 10:35:47')))
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
        $store = $this->factory()->store()->getStore('1');
        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $product = $this->createProduct('1');

        // Begin inventory
        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 3, 1)
                ->createInvoiceProduct($product, 2, 2)
            ->flush();

        // Day one
        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 3, 3)
            ->flush();

        $sale1 = $this->factory->createSale($store->id, "2014-01-02 12:23:12", 233);
        $this->factory->createSaleProduct(5, 4, $product, $sale1);
        $this->factory->flush();

        // Day two
        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 2, 2)
            ->flush();

        $sale1 = $this->factory->createSale($store->id, "2014-01-03 12:23:12", 233);
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
            "/api/1/stores/" . $store->id . "/reports/grossMargin",
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

        $accessToken = $this->factory->oauth()->authAsStoreManager($storeId);

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

    public function testGetDayGrossMarginReport()
    {
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');
        $productId = $this->createProduct('1');

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();


        $sale1 = $this->factory->createSale($store1->id, "2014-01-08 12:23:12", 1750);
        $this->factory->createSaleProduct(250, 7, $productId, $sale1);  // CoG: 800

        $sale2 = $this->factory->createSale($store1->id, "2014-01-08 16:23:12", 500);
        $this->factory->createSaleProduct(250, 2, $productId, $sale2);  // CoG: 300

        $sale3 = $this->factory->createSale($store1->id, "2014-01-10 12:23:12", 1500);
        $this->factory->createSaleProduct(250, 6, $productId, $sale3);  // CoG: 1150

        $sale4 = $this->factory->createSale($store2->id, "2014-01-08 12:30:12", 2100);
        $this->factory->createSaleProduct(300, 7, $productId, $sale4);  // CoG: 800

        $sale5 = $this->factory->createSale($store2->id, "2014-01-08 16:30:12", 600);
        $this->factory->createSaleProduct(300, 2, $productId, $sale5);  // CoG: 300

        $sale6 = $this->factory->createSale($store2->id, "2014-01-10 12:30:12", 1800);
        $this->factory->createSaleProduct(300, 6, $productId, $sale6);  // CoG: 1150
        $this->factory->flush();


        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();
        $this->getGrossMarginManager()->recalculateDayGrossMargin();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossMargin',
            null,
            array('time' => date('c', strtotime('2014-01-12 10:35:47')))
        );

        $expectedResponse = array(
            array(
                'date' => '2014-01-11T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-10T00:00:00+0400',
                'sum' => 1000,
            ),
            array(
                'date' => '2014-01-09T00:00:00+0400',
                'sum' => 0,
            ),
            array(
                'date' => '2014-01-08T00:00:00+0400',
                'sum' => 2750,
            ),
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
