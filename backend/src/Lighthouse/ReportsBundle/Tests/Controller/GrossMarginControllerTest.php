<?php

namespace Lighthouse\ReportsBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\JobBundle\Job\JobManager;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;

class GrossMarginControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->authenticateProject();
    }

    protected function prepareData()
    {
        $store = $this->factory()->store()->getStore();

        $productId1 = $this->createProductByName('1');
        $productId2 = $this->createProductByName('2');
        $productId3 = $this->createProductByName('3');

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-01 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 100)
                ->createInvoiceProduct($productId2, 10, 201)
                ->createInvoiceProduct($productId3, 5, 120)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-02 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-03 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 102)
                ->createInvoiceProduct($productId2, 5, 205)
                ->createInvoiceProduct($productId3, 10, 135)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-04 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 20, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-05 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-06 12:23:12'), $store->id)
                ->createInvoiceProduct($productId1, 5, 101)
                ->createInvoiceProduct($productId2, 5, 200)
                ->createInvoiceProduct($productId3, 10, 130)
            ->flush();


        $this->factory()
            ->receipt()
                ->createSale($store, '2014-01-02 12:23:12')
                ->createReceiptProduct($productId1, 3, 150)  // 450 - (3 x 100 = 300) = 150
                ->createReceiptProduct($productId2, 3, 250)  // 750 - (3 x 201 = 603) = 147
                ->createReceiptProduct($productId3, 3, 150)  // 450 - (3 x 120 = 360) = 90
            ->persist()
                ->createSale($store, '2014-01-03 12:23:12')
                ->createReceiptProduct($productId1, 3, 150)  // 450 - (2 x 100 + 1 x 101 = 301) = 149
                ->createReceiptProduct($productId2, 8, 250)  // 2000 - (7 x 201 + 1 x 200 = 1607) = 393
                ->createReceiptProduct($productId3, 3, 150)  // 450 - (2 x 120 + 1 x 130 = 370) = 80
            ->persist()
                ->createSale($store, '2014-01-05 12:23:12')
                ->createReceiptProduct($productId1, 8, 150)  // 1200 - (4 x 101 + 4 x 102 = 812) = 388
                ->createReceiptProduct($productId2, 3, 250)  // 750 - (3 x 200 = 600) = 150
                ->createReceiptProduct($productId3, 6, 150)  // 900 - (6 x 130 = 780) = 120
            ->persist()
                ->createSale($store, '2014-01-06 12:23:12')
                ->createReceiptProduct($productId1, 5, 150)  // 750 - (1 x 102 + 4 x 101 = 506) = 244
                ->createReceiptProduct($productId2, 6, 250)  // 1500 - (1 x 200 + 5 x 205 = 1225) = 275
                ->createReceiptProduct($productId3, 2, 150)  // 300 - (2 x 130 = 260) = 40
            ->persist()
                ->createSale($store, '2014-01-07 12:23:12')
                ->createReceiptProduct($productId1, 6, 150)  // 900 - (6 x 101 = 606) = 294
                ->createReceiptProduct($productId2, 3, 250)  // 750 - (3 x 200 = 600) = 150
                ->createReceiptProduct($productId3, 15, 150) // 2250 -(1x130+10x135+4x130=2000) = 250
            ->persist()
                ->createSale($store, '2014-01-08 12:23:12')
                ->createReceiptProduct($productId1, 8, 150)  // 1200 - (8 x 101 = 808) = 392
                ->createReceiptProduct($productId2, 3, 250)  // 750 - (3 x 200 = 600) = 150
                ->createReceiptProduct($productId3, 10, 150) // 1500 - (10 x 130 = 1300) = 200
            ->persist()
                ->createSale($store, '2014-01-09 12:23:12')
                ->createReceiptProduct($productId1, 3, 150)  // 450 - (3 x 101 = 303) = 147
                ->createReceiptProduct($productId2, 7, 250)  // 1750 - (7 x 200 = 1400) = 350
                ->createReceiptProduct($productId3, 1, 150)  // 150 - (1 x 130 = 130) = 20
            ->flush();

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

        $this->authenticateProject();
        // Calculate CostOfGoods
        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($storeId);

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            "GET",
            "/api/1/stores/{$storeId}/reports/grossMargin",
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

        $this->authenticateProject();
        // Calculate CostOfGoods
        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($storeId);

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
        $product = $this->createProductByName('1');

        $date = new DateTimestamp();

        $this->factory()
            ->invoice()
                ->createInvoice(
                    array('date' => $this->createDate('-2 days 08:00')),
                    $store->id
                )
                ->createInvoiceProduct($product, 50, 90)
            ->persist()
                ->createInvoice(
                    array('date' => $this->createDate('-1 day 08:00')),
                    $store->id
                )
                ->createInvoiceProduct($product, 35, 100)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store, $date->copy()->modify('-2 days 10:00'))
                ->createReceiptProduct($product, 25, 125)
            ->persist()
                ->createSale($store, $date->copy()->modify('-1 day 10:00'))
                ->createReceiptProduct($product, 30, 120)
            ->flush();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginManager()->recalculateStoreGrossMargin();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossMargin"
        );

        $expectedResponse = array(
            array(
                'date' => $this->createDate('-1 day 00:00'),
                'sum' => 850,
            ),
            array(
                'date' => $this->createDate('-2 days 00:00'),
                'sum' => 875,
            ),
        );

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testGetStoreGrossMarginReportsSimpleExampleOnBoard()
    {
        $store = $this->factory()->store()->getStore('1');
        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $product = $this->createProductByName('1');

        // Begin inventory
        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-01 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 3, 100)
                ->createInvoiceProduct($product, 1, 101)
                ->createInvoiceProduct($product, 2, 102)
            ->flush();


        // Inventory purchase
        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-02 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 1, 101)
                ->createInvoiceProduct($product, 2, 102)
            ->flush();


        // Sale
        $this->factory()
                ->receipt()
                    ->createSale($store, '2014-01-02 12:23:12')
                    ->createReceiptProduct($product, 7, 150)
                ->flush();

        // Calculate CostOfGoods
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
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
        $product = $this->createProductByName('1');

        // Begin inventory
        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-01 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 3, 1)
                ->createInvoiceProduct($product, 2, 2)
            ->flush();

        // Day one
        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-02 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 3, 3)
            ->flush();

        $this->factory()
                ->receipt()
                    ->createSale($store, '2014-01-02 12:23:12')
                    ->createReceiptProduct($product, 4, 5)
                ->flush();

        // Day two
        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-03 12:23:12'), $store->id)
                ->createInvoiceProduct($product, 2, 2)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store, '2014-01-03 12:23:12')
                ->createReceiptProduct($product, 3, 5)
            ->flush();


        // Calculate CostOfGoods
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();
        /* @var GrossMarginManager $grossMarginReportManager */
        $grossMarginReportManager = $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
        $grossMarginReportManager->recalculateStoreGrossMargin();


        $actualResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossMargin",
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
        $jobManager = $this->getContainer()->get('lighthouse.job.manager');
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

        $accessToken = $this->factory()->oauth()->authAsStoreManager($storeId);

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
        $productId = $this->createProductByName('1');

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-01 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-02 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-03 12:56'), $store1->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-01 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 100)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-02 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-01-03 12:00'), $store2->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store1, "2014-01-08 12:23:12")
                ->createReceiptProduct($productId, 7, 250)  // CoG: 800
            ->persist()
                ->createSale($store1, "2014-01-08 16:23:12")
                ->createReceiptProduct($productId, 2, 250)  // CoG: 300
            ->persist()
                ->createSale($store1, "2014-01-10 12:23:12")
                ->createReceiptProduct($productId, 6, 250)  // CoG: 1150
            ->persist()
                ->createSale($store2, "2014-01-08 12:30:12")
                ->createReceiptProduct($productId, 7, 300)  // CoG: 800
            ->persist()
                ->createSale($store2, "2014-01-08 16:30:12")
                ->createReceiptProduct($productId, 2, 300)  // CoG: 300
            ->persist()
                ->createSale($store2, "2014-01-10 12:30:12")
                ->createReceiptProduct($productId, 6, 300)  // CoG: 1150
            ->flush();


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
