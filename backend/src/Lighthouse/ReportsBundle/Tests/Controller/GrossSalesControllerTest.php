<?php

namespace Lighthouse\ReportsBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Output\NullOutput;
use DateTime;

class GrossSalesControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->authenticateProject();
    }

    /**
     * @return GrossSalesReportManager
     */
    protected function getGrossSalesReportService()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_sales.manager');
    }

    /**
     * @return array
     */
    protected function createSalesForTodayYesterdayWeekAgo()
    {
        $store = $this->factory()->store()->getStore();

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $this->factory()
            ->receipt()
                ->createSale($store, '8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '9:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '10:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '11:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
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
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-7 days 8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-7 days 9:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
                ->createReceiptProduct($products['3']->id, 3, 34.00)
            ->persist()
                ->createSale($store, '-7 days 10:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->flush();

        return array($store, $products);
    }

    public function testGetStoreGrossSalesReportsByTime()
    {
        list($store,) = $this->createSalesForTodayYesterdayWeekAgo();

        $this->getGrossSalesReportService()->recalculateStoreGrossSalesReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossSales",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => $this->createDate('10:00'),
                    'value' => 1207.06,
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => $this->createDate('-1 day 10:00'),
                    'value' => 1207.06,
                    'diff' => 0,
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-1 day 23:59:59'),
                    'value' => 1810.59,
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => $this->createDate('-7 day 10:00'),
                    'value' => 1309.06,
                    'diff' => -7.79
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-7 day 23:59:59'),
                    'value' => 1912.59,
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    public function testGetStoreGrossSalesReportsByTimeEmptyReports()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $accessToken = $this->factory()->oauth()->authAsStoreManager($storeId);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales',
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => $this->createDate('10:00'),
                    'value' => 0,
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => $this->createDate('-1 day 10:00'),
                    'value' => 0,
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-1 day 23:59:59'),
                    'value' => 0,
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => $this->createDate('-7 day 10:00'),
                    'value' => 0,
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-7 day 23:59:59'),
                    'value' => 0,
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    public function testAccessGetStoreGrossSalesReport()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $storeManagerToken = $this->factory()->oauth()->authAsStoreManager($storeId);
        $departmentManagerToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);
        $storeManagerOtherStoreToken = $this->factory()->oauth()->authAsRole(User::ROLE_STORE_MANAGER);
        $commercialManagerToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $departmentManagerOtherStoreToken = $this->factory()->oauth()->authAsRole(User::ROLE_DEPARTMENT_MANAGER);
        $administratorToken = $this->factory()->oauth()->authAsRole(User::ROLE_ADMINISTRATOR);


        $this->clientJsonRequest(
            $storeManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(200);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $departmentManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $storeManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $commercialManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $departmentManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $administratorToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);
    }

    /**
     * @return Store
     */
    protected function prepareStoreGrossSalesReportData()
    {
        $store = $this->factory()->store()->getStore();

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $this->factory()
            ->receipt()
                ->createSale($store, '7:25')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 8:01')
                ->createReceiptProduct($products['1']->id, 5, 34.77)
                ->createReceiptProduct($products['2']->id, 1, 64.79)
                ->createReceiptProduct($products['3']->id, 2, 43.55)
            ->persist()
                ->createSale($store, '-1 week 9:01')
                ->createReceiptProduct($products['1']->id, 10, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->flush();

        $storeGrossSalesReportService = $this->getGrossSalesReportService();

        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        return $store;
    }

    public function testGetStoreGrossSalesReportsDiffs()
    {
        $store = $this->prepareStoreGrossSalesReportData();
        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossSales",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => $this->createDate('10:00'),
                    'value' => 603.53,
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => $this->createDate('-1 day 10:00'),
                    'value' => 325.74,
                    'diff' => 85.28,
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-1 day 23:59:59'),
                    'value' => 325.74,
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => $this->createDate('-7 day 10:00'),
                    'value' => 846.92,
                    'diff' => -28.74
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-7 day 23:59:59'),
                    'value' => 846.92,
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    public function testGetStoreGrossSalesReportsDiffsPreviousDateIsZero()
    {
        $store = $this->prepareStoreGrossSalesReportData();
        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossSales",
            null,
            array('time' => $this->createDate('-7 day 10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => $this->createDate('-7 days 10:00'),
                    'value' => 846.92,
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => $this->createDate('-8 day 10:00'),
                    'value' => 0,
                    'diff' => 0,
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-8 day 23:59:59'),
                    'value' => 0,
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => $this->createDate('-14 day 10:00'),
                    'value' => 0,
                    'diff' => 0,
                ),
                'dayEnd' => array(
                    'date' => $this->createDate('-14 day 23:59:59'),
                    'value' => 0,
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    public function testGetStoreGrossSalesByHour()
    {
        list($store, $products) = $this->createSalesForTodayYesterdayWeekAgo();

        $storeOther = $this->factory()->store()->getStore('other');

        $this->factory()
            ->receipt()
                ->createSale($storeOther, '8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->flush();

        $this->getGrossSalesReportService()->recalculateStoreGrossSalesReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossSalesByHours",
            null,
            array('time' => $this->createDate('10:35:47', 'c'))
        );

        $this->assertResponseCode(200);

        $expectedYesterday = $expectedWeekAgo = array();

        for ($i = 0; $i <= 7; $i++) {
            $expectedYesterday[$i] = array(
                'dayHour' => $this->createDate("-1 day 0{$i}:00"),
                'hourSum' => 0,
            );
            $expectedWeekAgo[$i] = array(
                'dayHour' => $this->createDate("-1 week 0{$i}:00"),
                'hourSum' => 0,
            );
        }
        $expectedYesterday += array(
            8 => array(
                'dayHour' => $this->createDate('-1 day 08:00'),
                'hourSum' => 603.53,
            ),
            9 => array(
                'dayHour' => $this->createDate('-1 day 09:00'),
                'hourSum' => 603.53,
            ),
        );
        $expectedWeekAgo += array(
            8 => array(
                'dayHour' => $this->createDate('-1 week 08:00'),
                'hourSum' => 603.53,
            ),
            9 => array(
                'dayHour' => $this->createDate('-1 week 09:00'),
                'hourSum' => 705.53,
            ),
        );

        $expected = array(
            'today' => array(
                0 => array(
                    'dayHour' => $this->createDate('00:00'),
                    'hourSum' => 0,
                ),
                1 => array(
                    'dayHour' => $this->createDate('01:00'),
                    'hourSum' => 0,
                ),
                2 => array(
                    'dayHour' => $this->createDate('02:00'),
                    'hourSum' => 0,
                ),
                3 => array(
                    'dayHour' => $this->createDate('03:00'),
                    'hourSum' => 0,
                ),
                4 => array(
                    'dayHour' => $this->createDate('04:00'),
                    'hourSum' => 0,
                ),
                5 => array(
                    'dayHour' => $this->createDate('05:00'),
                    'hourSum' => 0,
                ),
                6 => array(
                    'dayHour' => $this->createDate('06:00'),
                    'hourSum' => 0,
                ),
                7 => array(
                    'dayHour' => $this->createDate('07:00'),
                    'hourSum' => 0,
                ),
                8 => array(
                    'dayHour' => $this->createDate('08:00'),
                    'hourSum' => 603.53,
                ),
                9 => array(
                    'dayHour' => $this->createDate('09:00'),
                    'hourSum' => 603.53,
                ),
            ),
            'yesterday' => $expectedYesterday,
            'weekAgo' => $expectedWeekAgo,
        );

        $this->assertEquals($expected, $response);
    }

    public function testAccessGetStoreGrossSalesReportByHours()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $storeManagerToken = $this->factory()->oauth()->authAsStoreManager($storeId);
        $departmentManagerToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);
        $storeManagerOtherStoreToken = $this->factory()->oauth()->authAsRole(User::ROLE_STORE_MANAGER);
        $commercialManagerToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $departmentManagerOtherStoreToken = $this->factory()->oauth()->authAsRole(User::ROLE_DEPARTMENT_MANAGER);
        $administratorToken = $this->factory()->oauth()->authAsRole(User::ROLE_ADMINISTRATOR);


        $this->clientJsonRequest(
            $storeManagerToken,
            'GET',
            "/api/1/stores/{$storeId}/reports/grossSalesByHours"
        );
        $this->assertResponseCode(200);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $departmentManagerToken,
            'GET',
            "/api/1/stores/{$storeId}/reports/grossSalesByHours"
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $storeManagerOtherStoreToken,
            'GET',
            "/api/1/stores/{$storeId}/reports/grossSalesByHours"
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $commercialManagerToken,
            'GET',
            "/api/1/stores/{$storeId}/reports/grossSalesByHours"
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $departmentManagerOtherStoreToken,
            'GET',
            "/api/1/stores/{$storeId}/reports/grossSalesByHours"
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $administratorToken,
            'GET',
            "/api/1/stores/{$storeId}/reports/grossSalesByHours"
        );
        $this->assertResponseCode(403);
    }

    public function testGetStoreGrossSalesByHourEmptyYesterday()
    {
        $store = $this->factory()->store()->getStore();

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $this->factory()
            ->receipt()
                ->createSale($store, '8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '9:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '10:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '11:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-7 days 8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-7 days 9:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
                ->createReceiptProduct($products['3']->id, 3, 34.00)
            ->persist()
                ->createSale($store, '-7 days 10:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->flush();

        $otherStore = $this->factory()->store()->getStore('other');

        $this->factory()
            ->receipt()
                ->createSale($otherStore, '8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->flush();

        $this->getGrossSalesReportService()->recalculateStoreGrossSalesReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossSalesByHours",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expectedYesterday = $expectedWeekAgo = array();

        for ($i = 0; $i <= 7; $i++) {
            $expectedYesterday[$i] = array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 day 0{$i}:00")),
                'hourSum' => 0,
            );
            $expectedWeekAgo[$i] = array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 week 0{$i}:00")),
                'hourSum' => 0,
            );
        }
        $expectedYesterday += array(
            8 => array(
                'dayHour' => $this->createDate('-1 day 08:00'),
                'hourSum' => 0,
            ),
            9 => array(
                'dayHour' => $this->createDate('-1 day 09:00'),
                'hourSum' => 0,
            ),
        );
        $expectedWeekAgo += array(
            8 => array(
                'dayHour' => $this->createDate('-1 week 08:00'),
                'hourSum' => 603.53,
            ),
            9 => array(
                'dayHour' => $this->createDate('-1 week 09:00'),
                'hourSum' => 705.53,
            ),
        );

        $expected = array(
            'today' => array(
                0 => array(
                    'dayHour' => $this->createDate('00:00'),
                    'hourSum' => 0,
                ),
                1 => array(
                    'dayHour' => $this->createDate('01:00'),
                    'hourSum' => 0,
                ),
                2 => array(
                    'dayHour' => $this->createDate('02:00'),
                    'hourSum' => 0,
                ),
                3 => array(
                    'dayHour' => $this->createDate('03:00'),
                    'hourSum' => 0,
                ),
                4 => array(
                    'dayHour' => $this->createDate('04:00'),
                    'hourSum' => 0,
                ),
                5 => array(
                    'dayHour' => $this->createDate('05:00'),
                    'hourSum' => 0,
                ),
                6 => array(
                    'dayHour' => $this->createDate('06:00'),
                    'hourSum' => 0,
                ),
                7 => array(
                    'dayHour' => $this->createDate('07:00'),
                    'hourSum' => 0,
                ),
                8 => array(
                    'dayHour' => $this->createDate('08:00'),
                    'hourSum' => 603.53,
                ),
                9 => array(
                    'dayHour' => $this->createDate('09:00'),
                    'hourSum' => 603.53,
                ),
            ),
            'yesterday' => $expectedYesterday,
            'weekAgo' => $expectedWeekAgo,
        );

        $this->assertEquals($expected, $response);
    }

    public function testGetStoreGrossSalesByHourEmptyAll()
    {
        $store = $this->factory()->store()->getStore();
        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $otherStore = $this->factory()->store()->getStore('other');

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $this->factory()
            ->receipt()
                ->createSale($otherStore, '8:01')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 64.79)
                ->createReceiptProduct($products['3']->id, 7, 43.55)
            ->flush();

        $this->getGrossSalesReportService()->recalculateStoreGrossSalesReport();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossSalesByHours",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expectedYesterday = $expectedWeekAgo = $expectedToday = array();

        for ($i = 0; $i <= 9; $i++) {
            $expectedToday[$i] = array(
                'dayHour' => date(DateTime::ISO8601, strtotime("0{$i}:00")),
                'hourSum' => 0,
            );
            $expectedYesterday[$i] = array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 day 0{$i}:00")),
                'hourSum' => 0,
            );
            $expectedWeekAgo[$i] = array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 week 0{$i}:00")),
                'hourSum' => 0,
            );
        }

        $expected = array(
            'today' => $expectedToday,
            'yesterday' => $expectedYesterday,
            'weekAgo' => $expectedWeekAgo,
        );

        $this->assertEquals($expected, $response);
    }

    public function testGetStoreGrossSalesByStore()
    {
        list($stores,) = $this->createSales();

        $storeGrossSalesReportService = $this->getGrossSalesReportService();
        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSalesByStores',
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expectedResponse = array(
            array(
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 23:00'),
                    'runningSum' => 708.91,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => $this->createDate('-2 days 23:00'),
                    'runningSum' => 715.55,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => $this->createDate('-8 days 23:00'),
                    'runningSum' => 360.86,
                ),
                'store' => array(
                    'id' => $stores['1']->id,
                    'name' => '1',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 23:00'),
                    'runningSum' => 1535.51,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => $this->createDate('-2 days 23:00'),
                    'runningSum' => 594.75,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => $this->createDate('-8 days 23:00'),
                    'runningSum' => 748.43,
                ),
                'store' => array(
                    'id' => $stores['2']->id,
                    'name' => '2',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 23:00'),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => $this->createDate('-2 days 23:00'),
                    'runningSum' => 838.84,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => $this->createDate('-8 days 23:00'),
                    'runningSum' => 965.58,
                ),
                'store' => array(
                    'id' => $stores['3']->id,
                    'name' => '3',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
        );

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetStoreGrossSalesByStoreEmpty()
    {
        $storeIds = $this->factory()->store()->getStoreIds(array('1', '2', '3'));
        // create store managers to be sure they would not get in serialization
        $this->factory()->store()->getStoreManager($storeIds['1']);
        $this->factory()->store()->getDepartmentManager($storeIds['1']);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSalesByStores',
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expectedEmptyResponse = array(
            array(
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 23:00'),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => $this->createDate('-2 days 23:00'),
                    'runningSum' => 0,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => $this->createDate('-8 days 23:00'),
                    'runningSum' => 0,
                ),
                'store' => array(
                    'id' => $storeIds['1'],
                    'name' => '1',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 23:00'),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => $this->createDate('-2 days 23:00'),
                    'runningSum' => 0,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => $this->createDate('-8 days 23:00'),
                    'runningSum' => 0,
                ),
                'store' => array(
                    'id' => $storeIds['2'],
                    'name' => '2',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 23:00'),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => $this->createDate('-2 days 23:00'),
                    'runningSum' => 0,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => $this->createDate('-8 days 23:00'),
                    'runningSum' => 0,
                ),
                'store' => array(
                    'id' => $storeIds['3'],
                    'name' => '3',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
        );

        $this->assertEquals($expectedEmptyResponse, $response);
        $this->assertSame($expectedEmptyResponse, $response);
    }

    public function testGetGrossSales()
    {
        $this->createSales();

        $storeGrossSalesReportService = $this->getGrossSalesReportService();
        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSales',
            null,
            array('time' => $this->createDate('10:35:47', 'c'))
        );

        $this->assertResponseCode(200);

        $expectedResponse = array(
            'yesterday' => array(
                'dayHour' => $this->createDate('-1 day 23:00'),
                'runningSum' => 2244.42,
                'hourSum' => 0,
            ),
            'twoDaysAgo' => array(
                'dayHour' => $this->createDate('-2 days 23:00'),
                'runningSum' => 2149.14,
                'hourSum' => 0,
            ),
            'eightDaysAgo' => array(
                'dayHour' => $this->createDate('-8 days 23:00'),
                'runningSum' => 2074.87,
                'hourSum' => 0,
            ),
        );

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetGrossSalesEmpty()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->authenticateProject();
        $storeGrossSalesReportService = $this->getGrossSalesReportService();
        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSales',
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expectedResponse = array(
            'yesterday' => array(
                'dayHour' => $this->createDate('-1 day 23:00'),
                'runningSum' => 0,
                'hourSum' => 0,
            ),
            'twoDaysAgo' => array(
                'dayHour' => $this->createDate('-2 days 23:00'),
                'runningSum' => 0,
                'hourSum' => 0,
            ),
            'eightDaysAgo' => array(
                'dayHour' => $this->createDate('-8 days 23:00'),
                'runningSum' => 0,
                'hourSum' => 0,
            ),
        );

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse, $response);
    }

    /**
     * @return array
     */
    protected function createSalesProducts()
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));
        // create store managers to be sure they would not get in serialization
        $this->factory()->store()->getStoreManager($stores['1']->id);
        $this->factory()->store()->getDepartmentManager($stores['1']->id);

        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(
                        '1.1.1' => '',
                        '1.1.2' => ''
                    ),
                    '1.2' => array(
                        '1.2.1' => ''
                    ),
                ),
                '2' => array(
                    '2.1' => array(
                        '2.1.1' => ''
                    )
                )
            )
        );

        $products = array(
            '1' => $this->factory()->catalog()->getProduct('1', $catalog['1.1.1']),
            '2' => $this->factory()->catalog()->getProduct('2', $catalog['1.1.1']),
            '3' => $this->factory()->catalog()->getProduct('3', $catalog['1.1.2']),
            '4' => $this->factory()->catalog()->getProduct('4', $catalog['2.1.1']),
            '5' => $this->factory()->catalog()->getProduct('5', $catalog['1.2.1'])
        );

        return array($stores, $catalog, $products);
    }

    /**
     * @return array
     */
    protected function createSales()
    {
        list($stores, $catalog, $products) = $this->createSalesProducts();

        // today, should not be counted
        $this->factory()
            ->receipt()
                ->createSale($stores['1'], '8:01')
                ->createReceiptProduct($products['1']->id, 34.77, 3)
                ->createReceiptProduct($products['2']->id, 4, 43.55)
                ->createReceiptProduct($products['3']->id, 2, 64.79)
            ->persist()
                ->createSale($stores['2'], '8:12')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 4, 43.55)
                ->createReceiptProduct($products['3']->id, 2, 64.79)
            ->persist()
                ->createSale($stores['3'], '8:03')
                ->createReceiptProduct($products['1']->id, 3, 34.77)
                ->createReceiptProduct($products['2']->id, 4, 43.55)
                ->createReceiptProduct($products['3']->id, 2, 64.79)
            ->persist()
            // yesterday, 3rd store has no sales
                ->createSale($stores['1'], '-1 day 8:01')
                ->createReceiptProduct($products['1']->id, 6, 34.77)
                ->createReceiptProduct($products['2']->id, 10, 43.55)
                ->createReceiptProduct($products['3']->id, 1, 64.79)
            ->persist()
                ->createSale($stores['2'], '-1 day 8:12')
                ->createReceiptProduct($products['1']->id, 5, 34.77)
                ->createReceiptProduct($products['2']->id, 3, 43.55)
                ->createReceiptProduct($products['3']->id, 19, 64.79)
            ->persist()
            // 2 days ago,
                ->createSale($stores['1'], '-2 days 8:01')
                ->createReceiptProduct($products['1']->id, 5, 34.77)
                ->createReceiptProduct($products['2']->id, 5, 43.55)
                ->createReceiptProduct($products['3']->id, 5, 64.79)
            ->persist()
                ->createSale($stores['2'], '-2 days 8:12')
                ->createReceiptProduct($products['1']->id, 4, 34.77)
                ->createReceiptProduct($products['2']->id, 6, 43.55)
                ->createReceiptProduct($products['3']->id, 3, 64.79)
            ->persist()
                ->createSale($stores['3'], '-2 days 8:03')
                ->createReceiptProduct($products['1']->id, 1, 34.77)
                ->createReceiptProduct($products['2']->id, 14, 43.55)
                ->createReceiptProduct($products['3']->id, 3, 64.79)
            ->persist()
            // 7 days ago, should not be counted
                ->createSale($stores['1'], '-7 days 8:01')
                ->createReceiptProduct($products['1']->id, 4, 34.77)
                ->createReceiptProduct($products['2']->id, 7, 43.55)
                ->createReceiptProduct($products['3']->id, 13, 64.79)
            ->persist()
                ->createSale($stores['2'], '-7 days 8:12')
                ->createReceiptProduct($products['1']->id, 23, 34.77)
                ->createReceiptProduct($products['2']->id, 1, 43.55)
                ->createReceiptProduct($products['3']->id, 12, 64.79)
            ->persist()
                ->createSale($stores['3'], '-7 days 8:03')
                ->createReceiptProduct($products['1']->id, 7, 34.77)
                ->createReceiptProduct($products['2']->id, 5, 43.55)
                ->createReceiptProduct($products['3']->id, 3, 64.79)
            ->persist()
            // 8 days ago
                ->createSale($stores['1'], '-8 days 8:01')
                ->createReceiptProduct($products['1']->id, 1, 34.77)
                ->createReceiptProduct($products['2']->id, 6, 43.55)
                ->createReceiptProduct($products['3']->id, 1, 64.79)
            ->persist()
                ->createSale($stores['2'], '-8 days 8:12')
                ->createReceiptProduct($products['1']->id, 8, 34.77)
                ->createReceiptProduct($products['2']->id, 10, 43.55)
                ->createReceiptProduct($products['1']->id, 1, 34.77)
            ->persist()
                ->createSale($stores['3'], '-8 days 8:03')
                ->createReceiptProduct($products['1']->id, 11, 34.77)
                ->createReceiptProduct($products['3']->id, 9, 64.79)
            ->persist()
            // 9 days ago, should not be counted
                ->createSale($stores['1'], '-9 days 10:01')
                ->createReceiptProduct($products['1']->id, 1, 34.77)
                ->createReceiptProduct($products['2']->id, 8, 43.55)
                ->createReceiptProduct($products['3']->id, 3, 64.79)
            ->persist()
                ->createSale($stores['2'], '-9 days 14:12')
                ->createReceiptProduct($products['1']->id, 4, 34.77)
                ->createReceiptProduct($products['2']->id, 5, 43.55)
                ->createReceiptProduct($products['3']->id, 6, 64.79)
            ->persist()
                ->createSale($stores['3'], '-9 days 16:03')
                ->createReceiptProduct($products['1']->id, 1, 34.77)
                ->createReceiptProduct($products['2']->id, 1, 43.55)
                ->createReceiptProduct($products['3']->id, 1, 64.79)
            ->flush();

        return array($stores, $catalog);
    }

    public function testGrossSalesByProducts()
    {
        $store = $this->factory()->store()->getStore('1');
        $otherStore = $this->factory()->store()->getStore('Other');
        $subCategory = $this->factory()->catalog()->createSubCategory();
        $subCategoryOther = $this->factory()->catalog()->createSubCategory(null, 'Other');

        $product1 = $this->factory()->catalog()->getProduct('1', $subCategory);
        $product2 = $this->factory()->catalog()->getProduct('2', $subCategory);
        $product3 = $this->factory()->catalog()->getProduct('3', $subCategoryOther);

        $this->factory()
            ->receipt()
                ->createSale($store, '8:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '9:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '10:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '11:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 8:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 9:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-1 days 10:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-7 days 8:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->persist()
                ->createSale($store, '-7 days 9:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
                ->createReceiptProduct($product1->id, 3, 34)
            ->persist()
                ->createSale($store, '-7 days 10:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($otherStore, '8:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->flush();

        $grossSalesReportManager = $this->getGrossSalesReportService();
        $grossSalesReportManager->recalculateGrossSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$subCategory->id}/reports/grossSalesByProducts",
            null,
            array('time' => $this->createDate('11:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($product1->id, '0.storeProduct.product.id', $response);
        Assert::assertJsonPathEquals($product2->id, '1.storeProduct.product.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['storeProduct'] = array(
            'product' => array(
                'id' => $response[0]['storeProduct']['product']['id']
            )
        );
        $filteredResponse[1]['storeProduct'] = array(
            'product' => array(
                'id' => $response[1]['storeProduct']['product']['id']
            )
        );

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => $this->createDate('10:00'),
                    'runningSum' => 312.93,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 10:00'),
                    'runningSum' => 312.93,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 10:00'),
                    'runningSum' => 414.93,
                ),
                'storeProduct' => array(
                    'product' => array(
                        'id' => $product1->id,
                    )
                )
            ),
            1 => array(
                'today' => array(
                    'dayHour' => $this->createDate('10:00'),
                    'runningSum' => 583.11,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 10:00'),
                    'runningSum' => 583.11,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 10:00'),
                    'runningSum' => 583.11,
                ),
                'storeProduct' => array(
                    'product' => array(
                        'id' => $product2->id,
                    )
                )
            ),
        );

        $this->assertEquals($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByProductsEmpty()
    {
        $store = $this->factory()->store()->getStore('1');
        $otherStore = $this->factory()->store()->getStore('Other');

        $subCategory = $this->factory()->catalog()->createSubCategory();
        $subCategoryOther = $this->factory()->catalog()->createSubCategory(null, 'Other');

        $product1 = $this->factory()->catalog()->getProduct('1', $subCategory);
        $product2 = $this->factory()->catalog()->getProduct('2', $subCategory);
        $product3 = $this->factory()->catalog()->getProduct('3', $subCategoryOther);

        $this->factory()
            ->receipt()
                ->createSale($otherStore, '8:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 3, 43.55)
            ->flush();

        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$subCategory->id}/reports/grossSalesByProducts",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        $expectedProduct2Today = array(
            'dayHour' => $this->createDate('09:00'),
            'runningSum' => 0,
        );
        $expectedProduct2Yesterday = array(
            'dayHour' => $this->createDate('-1 day 09:00'),
            'runningSum' => 0,
        );
        $expectedProduct2WeekAgo = array(
            'dayHour' => $this->createDate('-7 day 09:00'),
            'runningSum' => 0,
        );

        $expectedProduct1Today = array(
            'dayHour' => $this->createDate('09:00'),
            'runningSum' => 0,
        );
        $expectedProduct1Yesterday = array(
            'dayHour' => $this->createDate('-1 day 09:00'),
            'runningSum' => 0,
        );
        $expectedProduct1WeekAgo = array(
            'dayHour' => $this->createDate('-7 day 09:00'),
            'runningSum' => 0,
        );

        $this->assertCount(2, $response);

        Assert::assertJsonPathEquals($product1->id, '*.storeProduct.product.id', $response);
        Assert::assertJsonPathEquals($expectedProduct1Today, '*.today', $response);
        Assert::assertJsonPathEquals($expectedProduct1Yesterday, '*.yesterday', $response);
        Assert::assertJsonPathEquals($expectedProduct1WeekAgo, '*.weekAgo', $response);

        Assert::assertJsonPathEquals($product2->id, '*.storeProduct.product.id', $response);
        Assert::assertJsonPathEquals($expectedProduct2Today, '*.today', $response);
        Assert::assertJsonPathEquals($expectedProduct2Yesterday, '*.yesterday', $response);
        Assert::assertJsonPathEquals($expectedProduct2WeekAgo, '*.weekAgo', $response);
    }

    public function testGrossSalesByProductsMaxDepth()
    {
        $store = $this->factory()->store()->getStore('1');
        $otherStore = $this->factory()->store()->getStore('Other');

        $subCategory = $this->factory()->catalog()->createSubCategory();
        $subCategoryOther = $this->factory()->catalog()->createSubCategory(null, 'Other');

        $product1 = $this->factory()->catalog()->getProduct('1', $subCategory);
        $product2 = $this->factory()->catalog()->getProduct('2', $subCategory);
        $product3 = $this->factory()->catalog()->getProduct('3', $subCategoryOther);

        $this->factory()
            ->receipt()
                ->createSale($otherStore, '8:01')
                ->createReceiptProduct($product1->id, 3, 34.77)
                ->createReceiptProduct($product2->id, 3, 64.79)
                ->createReceiptProduct($product3->id, 7, 43.55)
            ->flush();

        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$subCategory->id}/reports/grossSalesByProducts",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '0.storeProduct.store.departmentManagers.*', $response);
        Assert::assertJsonPathCount(0, '0.storeProduct.store.storeManagers.*', $response);
        Assert::assertNotJsonHasPath('0.storeProduct.product.subCategories', $response);
    }

    public function testAccessGetGrossSalesByProductReport()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $storeManagerToken = $this->factory()->oauth()->authAsStoreManager($storeId);
        $departmentManagerToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);
        $storeManagerOtherStoreToken = $this->factory()->oauth()->authAsRole(User::ROLE_STORE_MANAGER);
        $commercialManagerToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $departmentManagerOtherStoreToken = $this->factory()->oauth()->authAsRole(User::ROLE_DEPARTMENT_MANAGER);
        $administratorToken = $this->factory()->oauth()->authAsRole(User::ROLE_ADMINISTRATOR);

        $subCategoryId = $this->factory()->catalog()->createSubCategory()->id;


        $this->clientJsonRequest(
            $storeManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(200);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $departmentManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $storeManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $commercialManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $departmentManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $administratorToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);
    }

    public function testGrossSalesBySubCategoriesReport()
    {
        list($stores, $catalog) = $this->createSales();

        $output = new NullOutput();
        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport(1);
        $this->getGrossSalesReportService()->recalculateGrossSalesBySubCategories($output);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/categories/{$catalog['1.1']->id}/reports/grossSalesBySubCategories",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalog['1.1.1']->id, '0.subCategory.id', $response);
        Assert::assertJsonPathEquals($catalog['1.1.2']->id, '1.subCategory.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['subCategory'] = array('id' => $filteredResponse[0]['subCategory']['id']);
        $filteredResponse[1]['subCategory'] = array('id' => $filteredResponse[1]['subCategory']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 278.51,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 644.12,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 443.93,
                ),
                'subCategory' => array(
                    'id' => $catalog['1.1.1']->id,
                )
            ),
            1 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 129.58,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 64.79,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 842.27,
                ),
                'subCategory' => array(
                    'id' => $catalog['1.1.2']->id,
                )
            )
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
        $this->assertSame($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesBySubCategoriesEmpty()
    {
        list($stores, $catalog) = $this->createSalesProducts();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/categories/{$catalog['1.1']->id}/reports/grossSalesBySubCategories",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalog['1.1.1']->id, '0.subCategory.id', $response);
        Assert::assertJsonPathEquals($catalog['1.1.2']->id, '1.subCategory.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['subCategory'] = array('id' => $filteredResponse[0]['subCategory']['id']);
        $filteredResponse[1]['subCategory'] = array('id' => $filteredResponse[1]['subCategory']['id']);

        $emptyDayReport = array(
            'today' => array(
                'dayHour' => $this->createDate('09:00'),
                'runningSum' => 0,
            ),
            'yesterday' => array(
                'dayHour' => $this->createDate('-1 day 09:00'),
                'runningSum' => 0,
            ),
            'weekAgo' => array(
                'dayHour' => $this->createDate('-7 day 09:00'),
                'runningSum' => 0,
            )
        );
        $expectedResponse = array(
            0 => $emptyDayReport + array('subCategory' => array('id' => $catalog['1.1.1']->id)),
            1 => $emptyDayReport + array('subCategory' => array('id' => $catalog['1.1.2']->id))
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesBySubCategoriesMaxDepth()
    {
        list($stores, $catalog) = $this->createSalesProducts();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/categories/{$catalog['1.1']->id}/reports/grossSalesBySubCategories",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalog['1.1.1']->id, '0.subCategory.id', $response);
        Assert::assertJsonPathEquals($catalog['1.1.2']->id, '1.subCategory.id', $response);

        Assert::assertNotJsonHasPath('0.subCategory.category', $response);
        Assert::assertNotJsonHasPath('1.subCategory.category', $response);
    }

    public function testGrossSalesByCategoriesReport()
    {
        list($stores, $catalogs) = $this->createSales();

        $output = new NullOutput();
        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport();
        $this->getGrossSalesReportService()->recalculateGrossSalesBySubCategories($output);
        $this->getGrossSalesReportService()->recalculateGrossSalesByCategories($output);
        $this->getGrossSalesReportService()->recalculateGrossSalesByGroups($output);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/groups/{$catalogs['1']->id}/reports/grossSalesByCategories",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogs['1.1']->id, '0.category.id', $response);
        Assert::assertJsonPathEquals($catalogs['1.2']->id, '1.category.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['category'] = array('id' => $filteredResponse[0]['category']['id']);
        $filteredResponse[1]['category'] = array('id' => $filteredResponse[1]['category']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 408.09,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 708.91,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 1286.2,
                ),
                'category' => array('id' => $catalogs['1.1']->id),
            ),
            1 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 0,
                ),
                'category' => array('id' => $catalogs['1.2']->id)
            ),
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
        $this->assertSame($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByCategoriesCategoryMaxDepth()
    {
        list($stores, $catalog) = $this->createSalesProducts();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/groups/{$catalog['1']->id}/reports/grossSalesByCategories",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalog['1.1']->id, '0.category.id', $response);
        Assert::assertNotJsonHasPath('0.category.group', $response);
        Assert::assertJsonPathCount(0, '0.category.subCategories.*', $response);
    }

    public function testGrossSalesByCategoriesEmpty()
    {
        list($stores, $catalog) = $this->createSalesProducts();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/groups/{$catalog['1']->id}/reports/grossSalesByCategories",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalog['1.1']->id, '0.category.id', $response);
        Assert::assertJsonPathEquals($catalog['1.2']->id, '1.category.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['category'] = array('id' => $filteredResponse[0]['category']['id']);
        $filteredResponse[1]['category'] = array('id' => $filteredResponse[1]['category']['id']);

        $emptyDayReport = array(
            'today' => array(
                'dayHour' => $this->createDate('09:00'),
                'runningSum' => 0,
            ),
            'yesterday' => array(
                'dayHour' => $this->createDate('-1 day 09:00'),
                'runningSum' => 0,
            ),
            'weekAgo' => array(
                'dayHour' => $this->createDate('-7 day 09:00'),
                'runningSum' => 0,
            )
        );
        $expectedResponse = array(
            0 => $emptyDayReport + array('category' => array('id' => $catalog['1.1']->id)),
            1 => $emptyDayReport + array('category' => array('id' => $catalog['1.2']->id))
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByGroupsReport()
    {
        list($stores, $catalogs) = $this->createSales();

        $output = new NullOutput();
        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport();
        $this->getGrossSalesReportService()->recalculateGrossSalesBySubCategories($output, 1);
        $this->getGrossSalesReportService()->recalculateGrossSalesByCategories($output, 1);
        $this->getGrossSalesReportService()->recalculateGrossSalesByGroups($output, 1);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/reports/grossSalesByGroups",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogs['1']->id, '0.group.id', $response);
        Assert::assertJsonPathEquals($catalogs['2']->id, '1.group.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['group'] = array('id' => $filteredResponse[0]['group']['id']);
        $filteredResponse[1]['group'] = array('id' => $filteredResponse[1]['group']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 408.09,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 708.91,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 1286.2,
                ),
                'group' => array('id' => $catalogs['1']->id)
            ),
            1 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 0,
                ),
                'group' => array('id' => $catalogs['2']->id),
            )
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
        $this->assertSame($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByGroupsEmpty()
    {
        list($stores, $catalog) = $this->createSalesProducts();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/reports/grossSalesByGroups",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalog['1']->id, '0.group.id', $response);
        Assert::assertJsonPathEquals($catalog['2']->id, '1.group.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['group'] = array('id' => $filteredResponse[0]['group']['id']);
        $filteredResponse[1]['group'] = array('id' => $filteredResponse[1]['group']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 0,
                ),
                'group' => array('id' => $catalog['1']->id)
            ),
            1 => array(
                'today' => array(
                    'dayHour' => $this->createDate('09:00'),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => $this->createDate('-1 day 09:00'),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => $this->createDate('-7 day 09:00'),
                    'runningSum' => 0,
                ),
                'group' => array('id' => $catalog['2']->id)
            )
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByGroupsMaxDepth()
    {
        list($stores, $catalog) = $this->createSalesProducts();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($stores['1']->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$stores['1']->id}/reports/grossSalesByGroups",
            null,
            array('time' => $this->createDate('10:35:47', 'c') )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalog['1']->id, '0.group.id', $response);
        Assert::assertJsonPathEquals($catalog['2']->id, '1.group.id', $response);

        Assert::assertJsonPathCount(0, '0.group.categories.*', $response);
        Assert::assertJsonPathCount(0, '1.group.categories.*', $response);
    }
}
