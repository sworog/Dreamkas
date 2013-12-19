<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use DateTime;
use Symfony\Component\Console\Output\NullOutput;

class ReportControllerTest extends WebTestCase
{
    /**
     * @return GrossSalesReportManager
     */
    public function getGrossSalesReportService()
    {
        return $this->getContainer()->get('lighthouse.core.document.report.gross_sales.manager');
    }

    public function testGetStoreGrossSalesReportsByTime()
    {
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $sales = array(
            array(
                'storeId' => $storeId,
                'createdDate' => "8:01",
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
                'createdDate' => "9:01",
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
                'createdDate' => "10:01",
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
                'createdDate' => "11:01",
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
                'createdDate' => "-7 days 8:01",
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
                'createdDate' => "-7 days 9:01",
                'sumTotal' => 705.53,
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
                    array(
                        'productId' => $product1Id,
                        'quantity' => 3,
                        'price' => 34.00
                    ),
                ),
            ),
            array(
                'storeId' => $storeId,
                'createdDate' => "-7 days 10:01",
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

        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("10:00")),
                    'value' => 1207.06,
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 10:00")),
                    'value' => 1207.06,
                    'diff' => 0,
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 23:59:59")),
                    'value' => 1810.59,
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 10:00")),
                    'value' => 1309.06,
                    'diff' => -7.79
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 23:59:59")),
                    'value' => 1912.59,
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    public function testGetStoreGrossSalesReportsByTimeEmptyReports()
    {
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("10:00")),
                    'value' => 0,
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 10:00")),
                    'value' => 0,
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 23:59:59")),
                    'value' => 0,
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 10:00")),
                    'value' => 0,
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 23:59:59")),
                    'value' => 0,
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    public function testAccessGetStoreGrossSalesReport()
    {
        $storeId = $this->factory->getStore();
        $storeManagerToken = $this->factory->authAsStoreManager($storeId);
        $departmentManagerToken = $this->factory->authAsDepartmentManager($storeId);
        $storeManagerOtherStoreToken = $this->factory->authAsRole(User::ROLE_STORE_MANAGER);
        $commercialManagerToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $departmentManagerOtherStoreToken = $this->factory->authAsRole(User::ROLE_DEPARTMENT_MANAGER);
        $administratorToken = $this->factory->authAsRole(User::ROLE_ADMINISTRATOR);


        $this->clientJsonRequest(
            $storeManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $departmentManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $storeManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $commercialManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $departmentManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $administratorToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales'
        );
        $this->assertResponseCode(403);
    }

    public function testGetStoreGrossSalesReportsDiffs()
    {
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $sales = array(
            array(
                'storeId' => $storeId,
                'createdDate' => "7:25",
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
                'createdDate' => "-1 days 8:01",
                'sumTotal' => 325.74,
                'positions' => array(
                    array(
                        'productId' => $product1Id,
                        'quantity' => 5,
                        'price' => 34.77
                    ),
                    array(
                        'productId' => $product2Id,
                        'quantity' => 1,
                        'price' => 64.79
                    ),
                    array(
                        'productId' => $product3Id,
                        'quantity' => 2,
                        'price' => 43.55,
                    ),
                ),
            ),
            array(
                'storeId' => $storeId,
                'createdDate' => "-1 week 9:01",
                'sumTotal' => 846.92,
                'positions' => array(
                    array(
                        'productId' => $product1Id,
                        'quantity' => 10,
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

        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSales',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("10:00")),
                    'value' => 603.53,
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 10:00")),
                    'value' => 325.74,
                    'diff' => 85.28,
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 23:59:59")),
                    'value' => 325.74,
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 10:00")),
                    'value' => 846.92,
                    'diff' => -28.74
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 23:59:59")),
                    'value' => 846.92,
                ),
            ),
        );

        $this->assertSame($expected, $response);
    }

    public function testGetStoreGrossSalesByHour()
    {
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);

        $storeOtherId = $this->factory->getStore("other");

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $sales = array(
            array(
                'storeId' => $storeId,
                'createdDate' => "8:01",
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
                'createdDate' => "9:01",
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
                'createdDate' => "10:01",
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
                'createdDate' => "11:01",
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
                'createdDate' => "-7 days 8:01",
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
                'createdDate' => "-7 days 9:01",
                'sumTotal' => 705.53,
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
                    array(
                        'productId' => $product1Id,
                        'quantity' => 3,
                        'price' => 34.00
                    ),
                ),
            ),
            array(
                'storeId' => $storeId,
                'createdDate' => "-7 days 10:01",
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


        $salesInOtherStore = array(
            array(
                'storeId' => $storeOtherId,
                'createdDate' => "8:01",
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

        $this->factory->createSales($salesInOtherStore);


        $storeGrossSalesReportService = $this->getGrossSalesReportService();

        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours',
            null,
            array('time' => date('c', strtotime("10:35:47")))
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
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 day 08:00")),
                'hourSum' => 603.53,
            ),
            9 => array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 day 09:00")),
                'hourSum' => 603.53,
            ),
        );
        $expectedWeekAgo += array(
            8 => array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 week 08:00")),
                'hourSum' => 603.53,
            ),
            9 => array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 week 09:00")),
                'hourSum' => 705.53,
            ),
        );

        $expected = array(
            'today' => array(
                0 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("00:00")),
                    'hourSum' => 0,
                ),
                1 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("01:00")),
                    'hourSum' => 0,
                ),
                2 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("02:00")),
                    'hourSum' => 0,
                ),
                3 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("03:00")),
                    'hourSum' => 0,
                ),
                4 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("04:00")),
                    'hourSum' => 0,
                ),
                5 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("05:00")),
                    'hourSum' => 0,
                ),
                6 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("06:00")),
                    'hourSum' => 0,
                ),
                7 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("07:00")),
                    'hourSum' => 0,
                ),
                8 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("08:00")),
                    'hourSum' => 603.53,
                ),
                9 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("09:00")),
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
        $storeId = $this->factory->getStore();
        $storeManagerToken = $this->factory->authAsStoreManager($storeId);
        $departmentManagerToken = $this->factory->authAsDepartmentManager($storeId);
        $storeManagerOtherStoreToken = $this->factory->authAsRole(User::ROLE_STORE_MANAGER);
        $commercialManagerToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $departmentManagerOtherStoreToken = $this->factory->authAsRole(User::ROLE_DEPARTMENT_MANAGER);
        $administratorToken = $this->factory->authAsRole(User::ROLE_ADMINISTRATOR);


        $this->clientJsonRequest(
            $storeManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours'
        );
        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $departmentManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $storeManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $commercialManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $departmentManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $administratorToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours'
        );
        $this->assertResponseCode(403);
    }

    public function testGetStoreGrossSalesByHourEmptyYesterday()
    {
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);

        $storeOtherId = $this->factory->getStore("other");

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $sales = array(
            array(
                'storeId' => $storeId,
                'createdDate' => "8:01",
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
                'createdDate' => "9:01",
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
                'createdDate' => "10:01",
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
                'createdDate' => "11:01",
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
                'createdDate' => "-7 days 8:01",
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
                'createdDate' => "-7 days 9:01",
                'sumTotal' => 705.53,
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
                    array(
                        'productId' => $product1Id,
                        'quantity' => 3,
                        'price' => 34.00
                    ),
                ),
            ),
            array(
                'storeId' => $storeId,
                'createdDate' => "-7 days 10:01",
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


        $salesInOtherStore = array(
            array(
                'storeId' => $storeOtherId,
                'createdDate' => "8:01",
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

        $this->factory->createSales($salesInOtherStore);


        $storeGrossSalesReportService = $this->getGrossSalesReportService();

        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours',
            null,
            array('time' => date('c', strtotime("10:35:47")))
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
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 day 08:00")),
                'hourSum' => 0,
            ),
            9 => array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 day 09:00")),
                'hourSum' => 0,
            ),
        );
        $expectedWeekAgo += array(
            8 => array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 week 08:00")),
                'hourSum' => 603.53,
            ),
            9 => array(
                'dayHour' => date(DateTime::ISO8601, strtotime("-1 week 09:00")),
                'hourSum' => 705.53,
            ),
        );

        $expected = array(
            'today' => array(
                0 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("00:00")),
                    'hourSum' => 0,
                ),
                1 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("01:00")),
                    'hourSum' => 0,
                ),
                2 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("02:00")),
                    'hourSum' => 0,
                ),
                3 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("03:00")),
                    'hourSum' => 0,
                ),
                4 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("04:00")),
                    'hourSum' => 0,
                ),
                5 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("05:00")),
                    'hourSum' => 0,
                ),
                6 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("06:00")),
                    'hourSum' => 0,
                ),
                7 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("07:00")),
                    'hourSum' => 0,
                ),
                8 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("08:00")),
                    'hourSum' => 603.53,
                ),
                9 => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime("09:00")),
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
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);

        $storeOtherId = $this->factory->getStore("other");

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');


        $salesInOtherStore = array(
            array(
                'storeId' => $storeOtherId,
                'createdDate' => "8:01",
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

        $this->factory->createSales($salesInOtherStore);


        $storeGrossSalesReportService = $this->getGrossSalesReportService();

        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/reports/grossSalesByHours',
            null,
            array('time' => date('c', strtotime("10:35:47")))
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
        list($storeIds,) = $this->createSales();

        $storeGrossSalesReportService = $this->getGrossSalesReportService();
        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $accessToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSalesByStores',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expectedResponse = array(
            array(
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                    'runningSum' => 708.91,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                    'runningSum' => 715.55,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
                    'runningSum' => 360.86,
                ),
                'store' => array(
                    'id' => $storeIds['1'],
                    'number' => '1',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                    'runningSum' => 1535.51,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                    'runningSum' => 594.75,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
                    'runningSum' => 748.43,
                ),
                'store' => array(
                    'id' => $storeIds['2'],
                    'number' => '2',
                    'address' => '2',
                    'contacts' => '2',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                    'runningSum' => 838.84,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
                    'runningSum' => 965.58,
                ),
                'store' => array(
                    'id' => $storeIds['3'],
                    'number' => '3',
                    'address' => '3',
                    'contacts' => '3',
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
        $storeIds = $this->factory->getStores(array('1', '2', '3'));
        // create store managers to be sure they would not get in serialization
        $this->factory->getStoreManager($storeIds['1']);
        $this->factory->getDepartmentManager($storeIds['1']);

        $accessToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSalesByStores',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expectedEmptyResponse = array(
            array(
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                    'runningSum' => 0,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
                    'runningSum' => 0,
                ),
                'store' => array(
                    'id' => $storeIds['1'],
                    'number' => '1',
                    'address' => '1',
                    'contacts' => '1',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                    'runningSum' => 0,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
                    'runningSum' => 0,
                ),
                'store' => array(
                    'id' => $storeIds['2'],
                    'number' => '2',
                    'address' => '2',
                    'contacts' => '2',
                    'departments' => array(),
                    'storeManagers' => array(),
                    'departmentManagers' => array(),
                ),
            ),
            array(
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                    'runningSum' => 0,
                ),
                'twoDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                    'runningSum' => 0,
                ),
                'eightDaysAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
                    'runningSum' => 0,
                ),
                'store' => array(
                    'id' => $storeIds['3'],
                    'number' => '3',
                    'address' => '3',
                    'contacts' => '3',
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

        $accessToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSales',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expectedResponse = array(
            'yesterday' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                'runningSum' => 2244.42,
                'hourSum' => 0,
            ),
            'twoDaysAgo' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                'runningSum' => 2149.14,
                'hourSum' => 0,
            ),
            'eightDaysAgo' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
                'runningSum' => 2074.87,
                'hourSum' => 0,
            ),
        );

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetGrossSalesEmpty()
    {
        $storeGrossSalesReportService = $this->getGrossSalesReportService();
        $storeGrossSalesReportService->recalculateStoreGrossSalesReport();

        $accessToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/grossSales',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expectedResponse = array(
            'yesterday' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 23:00')),
                'runningSum' => 0,
                'hourSum' => 0,
            ),
            'twoDaysAgo' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-2 days 23:00')),
                'runningSum' => 0,
                'hourSum' => 0,
            ),
            'eightDaysAgo' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-8 days 23:00')),
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
        $storeIds = $this->factory->getStores(array('1', '2', '3'));
        // create store managers to be sure they would not get in serialization
        $this->factory->getStoreManager($storeIds['1']);
        $this->factory->getDepartmentManager($storeIds['1']);

        $catalogIds = $this->createCatalog(
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
            ),
            false
        );

        $productIds = array();
        $productIds['1'] = $this->createProduct(array('sku' => '1'), $catalogIds['1.1.1']);
        $productIds['2'] = $this->createProduct(array('sku' => '2'), $catalogIds['1.1.1']);
        $productIds['3'] = $this->createProduct(array('sku' => '3'), $catalogIds['1.1.2']);
        $productIds['4'] = $this->createProduct(array('sku' => '4'), $catalogIds['2.1.1']);
        $productIds['5'] = $this->createProduct(array('sku' => '5'), $catalogIds['1.2.1']);

        return array($storeIds, $productIds, $catalogIds);
    }

    /**
     * @return array
     */
    protected function createSales()
    {
        list($storeIds, $productIds, $catalogIds) = $this->createSalesProducts();

        // today, should not be counted
        $sale = $this->factory->createSale($storeIds['1'], '8:01', 408.09);
        $this->factory->createSaleProduct(34.77, 3, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 4, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 2, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['2'], '8:12', 0);
        $this->factory->createSaleProduct(34.77, 3, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 4, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 2, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['3'], '8:03', 0);
        $this->factory->createSaleProduct(34.77, 3, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 4, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 2, $productIds['3'], $sale);

        // yesterday, 3rd store has no sales
        $sale = $this->factory->createSale($storeIds['1'], '-1 day 8:01', 708.91);
        $this->factory->createSaleProduct(34.77, 6, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 10, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 1, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['2'], '-1 day 8:12', 1535.51);
        $this->factory->createSaleProduct(34.77, 5, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 3, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 19, $productIds['3'], $sale);

        // 2 days ago,
        $sale = $this->factory->createSale($storeIds['1'], '- 2 days 8:01', 715.55);
        $this->factory->createSaleProduct(34.77, 5, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 5, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 5, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['2'], '-2 days 8:12', 594.75);
        $this->factory->createSaleProduct(34.77, 4, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 6, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 3, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['3'], '-2 days 8:03', 838.84);
        $this->factory->createSaleProduct(34.77, 1, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 14, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 3, $productIds['3'], $sale);

        // 7 days ago, should not be counted
        $sale = $this->factory->createSale($storeIds['1'], '-7 days 8:01', 0);
        $this->factory->createSaleProduct(34.77, 4, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 7, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 13, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['2'], '-7 days 8:12', 0);
        $this->factory->createSaleProduct(34.77, 23, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 1, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 12, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['3'], '-7 days 8:03', 0);
        $this->factory->createSaleProduct(34.77, 7, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 5, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 3, $productIds['3'], $sale);

        // 8 days ago
        $sale = $this->factory->createSale($storeIds['1'], '-8 days 8:01', 360.86);
        $this->factory->createSaleProduct(34.77, 1, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 6, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 1, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['2'], '-8 days 8:12', 748.43);
        $this->factory->createSaleProduct(34.77, 8, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 10, $productIds['2'], $sale);
        $this->factory->createSaleProduct(34.77, 1, $productIds['1'], $sale);

        $sale = $this->factory->createSale($storeIds['3'], '-8 days 8:03', 965.58);
        $this->factory->createSaleProduct(34.77, 11, $productIds['1'], $sale);
        $this->factory->createSaleProduct(64.79, 9, $productIds['3'], $sale);

        // 9 days ago, should not be counted
        $sale = $this->factory->createSale($storeIds['1'], '-9 days 10:01', 0);
        $this->factory->createSaleProduct(34.77, 1, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 8, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 3, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['2'], '-9 days 14:12', 0);
        $this->factory->createSaleProduct(34.77, 4, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 5, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 6, $productIds['3'], $sale);

        $sale = $this->factory->createSale($storeIds['3'], '-9 days 16:03', 0);
        $this->factory->createSaleProduct(34.77, 1, $productIds['1'], $sale);
        $this->factory->createSaleProduct(43.55, 1, $productIds['2'], $sale);
        $this->factory->createSaleProduct(64.79, 1, $productIds['3'], $sale);

        $this->factory->flush();

        return array($storeIds, $productIds, $catalogIds);
    }

    public function testGrossSalesByProducts()
    {
        $storeId = $this->factory->getStore('1');
        $storeOtherId = $this->factory->getStore('Other');
        $subCategoryId = $this->createSubCategory();
        $subCategoryOtherId = $this->createSubCategory(null, 'Other');
        $product1Id = $this->createProduct('1', $subCategoryId);
        $product2Id = $this->createProduct('2', $subCategoryId);
        $product3Id = $this->createProduct('3', $subCategoryOtherId);

        $sales = array(
            array(
                'storeId' => $storeId,
                'createdDate' => "8:01",
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
                'createdDate' => "9:01",
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
                'createdDate' => "10:01",
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
                'createdDate' => "11:01",
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
                'createdDate' => "-7 days 8:01",
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
                'createdDate' => "-7 days 9:01",
                'sumTotal' => 705.53,
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
                    array(
                        'productId' => $product1Id,
                        'quantity' => 3,
                        'price' => 34.00
                    ),
                ),
            ),
            array(
                'storeId' => $storeId,
                'createdDate' => "-7 days 10:01",
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

        $salesInOtherStore = array(
            array(
                'storeId' => $storeOtherId,
                'createdDate' => "8:01",
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
        $this->factory->createSales($salesInOtherStore);

        $grossSalesReportManager = $this->getGrossSalesReportService();
        $grossSalesReportManager->recalculateGrossSalesProductReport();

        $accessToken = $this->factory->authAsStoreManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/' . $subCategoryId . '/reports/grossSalesByProducts',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expectedProduct2Today = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
            'runningSum' => 583.11,
        );
        $expectedProduct2Yesterday = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
            'runningSum' => 583.11,
        );
        $expectedProduct2WeekAgo = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
            'runningSum' => 583.11,
        );

        $expectedProduct1Today = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
            'runningSum' => 312.93,
        );
        $expectedProduct1Yesterday = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
            'runningSum' => 312.93,
        );
        $expectedProduct1WeekAgo = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
            'runningSum' => 414.93,
        );

        $this->assertCount(2, $response);

        Assert::assertJsonPathEquals($product1Id, '*.storeProduct.product.id', $response);
        Assert::assertJsonPathEquals($expectedProduct1Today, '*.today', $response);
        Assert::assertJsonPathEquals($expectedProduct1Yesterday, '*.yesterday', $response);
        Assert::assertJsonPathEquals($expectedProduct1WeekAgo, '*.weekAgo', $response);

        Assert::assertJsonPathEquals($product2Id, '*.storeProduct.product.id', $response);
        Assert::assertJsonPathEquals($expectedProduct2Today, '*.today', $response);
        Assert::assertJsonPathEquals($expectedProduct2Yesterday, '*.yesterday', $response);
        Assert::assertJsonPathEquals($expectedProduct2WeekAgo, '*.weekAgo', $response);
    }

    public function testGrossSalesByProductsEmpty()
    {
        $storeId = $this->factory->getStore('1');
        $storeOtherId = $this->factory->getStore('Other');
        $subCategoryId = $this->createSubCategory();
        $subCategoryOtherId = $this->createSubCategory(null, 'Other');
        $product1Id = $this->createProduct('1', $subCategoryId);
        $product2Id = $this->createProduct('2', $subCategoryId);
        $product3Id = $this->createProduct('3', $subCategoryOtherId);

        $salesInOtherStore = array(
            array(
                'storeId' => $storeOtherId,
                'createdDate' => "8:01",
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
        $this->factory->createSales($salesInOtherStore);

        $grossSalesReportManager = $this->getGrossSalesReportService();
        $grossSalesReportManager->recalculateGrossSalesProductReport();

        $accessToken = $this->factory->authAsStoreManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/' . $subCategoryId . '/reports/grossSalesByProducts',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expectedProduct2Today = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
            'runningSum' => 0,
        );
        $expectedProduct2Yesterday = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
            'runningSum' => 0,
        );
        $expectedProduct2WeekAgo = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
            'runningSum' => 0,
        );

        $expectedProduct1Today = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
            'runningSum' => 0,
        );
        $expectedProduct1Yesterday = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
            'runningSum' => 0,
        );
        $expectedProduct1WeekAgo = array(
            'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
            'runningSum' => 0,
        );

        $this->assertCount(2, $response);

        Assert::assertJsonPathEquals($product1Id, '*.storeProduct.product.id', $response);
        Assert::assertJsonPathEquals($expectedProduct1Today, '*.today', $response);
        Assert::assertJsonPathEquals($expectedProduct1Yesterday, '*.yesterday', $response);
        Assert::assertJsonPathEquals($expectedProduct1WeekAgo, '*.weekAgo', $response);

        Assert::assertJsonPathEquals($product2Id, '*.storeProduct.product.id', $response);
        Assert::assertJsonPathEquals($expectedProduct2Today, '*.today', $response);
        Assert::assertJsonPathEquals($expectedProduct2Yesterday, '*.yesterday', $response);
        Assert::assertJsonPathEquals($expectedProduct2WeekAgo, '*.weekAgo', $response);
    }

    public function testAccessGetGrossSalesByProductReport()
    {
        $storeId = $this->factory->getStore();
        $storeManagerToken = $this->factory->authAsStoreManager($storeId);
        $departmentManagerToken = $this->factory->authAsDepartmentManager($storeId);
        $storeManagerOtherStoreToken = $this->factory->authAsRole(User::ROLE_STORE_MANAGER);
        $commercialManagerToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $departmentManagerOtherStoreToken = $this->factory->authAsRole(User::ROLE_DEPARTMENT_MANAGER);
        $administratorToken = $this->factory->authAsRole(User::ROLE_ADMINISTRATOR);

        $subCategoryId = $this->createSubCategory();


        $this->clientJsonRequest(
            $storeManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $departmentManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $storeManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $commercialManagerToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $departmentManagerOtherStoreToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);

        $this->clientJsonRequest(
            $administratorToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/'. $subCategoryId .'/reports/grossSalesByProducts'
        );
        $this->assertResponseCode(403);
    }

    public function testGrossSalesBySubCategoriesReport()
    {
        list($storeIds,, $catalogIds) = $this->createSales();

        $output = new NullOutput();
        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport();
        $this->getGrossSalesReportService()->recalculateGrossSalesBySubCategories($output);

        $accessToken = $this->factory->authAsStoreManager($storeIds['1']);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeIds['1']}/categories/{$catalogIds['1.1']}/reports/grossSalesBySubCategories",
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogIds['1.1.1'], '0.subCategory.id', $response);
        Assert::assertJsonPathEquals($catalogIds['1.1.2'], '1.subCategory.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['subCategory'] = array('id' => $filteredResponse[0]['subCategory']['id']);
        $filteredResponse[1]['subCategory'] = array('id' => $filteredResponse[1]['subCategory']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 278.51,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 644.12,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 443.93,
                ),
                'subCategory' => array(
                    'id' => $catalogIds['1.1.1'],
                )
            ),
            1 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 129.58,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 64.79,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 842.27,
                ),
                'subCategory' => array(
                    'id' => $catalogIds['1.1.2'],
                )
            )
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
        $this->assertSame($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesBySubCategoriesEmpty()
    {
        list($storeIds,, $catalogIds) = $this->createSalesProducts();

        $accessToken = $this->factory->authAsStoreManager($storeIds['1']);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeIds['1']}/categories/{$catalogIds['1.1']}/reports/grossSalesBySubCategories",
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogIds['1.1.1'], '0.subCategory.id', $response);
        Assert::assertJsonPathEquals($catalogIds['1.1.2'], '1.subCategory.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['subCategory'] = array('id' => $filteredResponse[0]['subCategory']['id']);
        $filteredResponse[1]['subCategory'] = array('id' => $filteredResponse[1]['subCategory']['id']);

        $emptyDayReport = array(
            'today' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                'runningSum' => 0,
            ),
            'yesterday' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                'runningSum' => 0,
            ),
            'weekAgo' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                'runningSum' => 0,
            )
        );
        $expectedResponse = array(
            0 => $emptyDayReport + array('subCategory' => array('id' => $catalogIds['1.1.1'])),
            1 => $emptyDayReport + array('subCategory' => array('id' => $catalogIds['1.1.2']))
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByCategoriesReport()
    {
        list($storeIds,, $catalogIds) = $this->createSales();

        $output = new NullOutput();
        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport();
        $this->getGrossSalesReportService()->recalculateGrossSalesBySubCategories($output);
        $this->getGrossSalesReportService()->recalculateGrossSalesByCategories($output);
        $this->getGrossSalesReportService()->recalculateGrossSalesByGroups($output);

        $accessToken = $this->factory->authAsStoreManager($storeIds['1']);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeIds['1']}/groups/{$catalogIds['1']}/reports/grossSalesByCategories",
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogIds['1.1'], '0.category.id', $response);
        Assert::assertJsonPathEquals($catalogIds['1.2'], '1.category.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['category'] = array('id' => $filteredResponse[0]['category']['id']);
        $filteredResponse[1]['category'] = array('id' => $filteredResponse[1]['category']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 408.09,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 708.91,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 1286.2,
                ),
                'category' => array('id' => $catalogIds['1.1']),
            ),
            1 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 0,
                ),
                'category' => array('id' => $catalogIds['1.2'])
            ),
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
        $this->assertSame($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByCategoriesEmpty()
    {
        list($storeIds,, $catalogIds) = $this->createSalesProducts();

        $accessToken = $this->factory->authAsStoreManager($storeIds['1']);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeIds['1']}/groups/{$catalogIds['1']}/reports/grossSalesByCategories",
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogIds['1.1'], '0.category.id', $response);
        Assert::assertJsonPathEquals($catalogIds['1.2'], '1.category.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['category'] = array('id' => $filteredResponse[0]['category']['id']);
        $filteredResponse[1]['category'] = array('id' => $filteredResponse[1]['category']['id']);

        $emptyDayReport = array(
            'today' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                'runningSum' => 0,
            ),
            'yesterday' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                'runningSum' => 0,
            ),
            'weekAgo' => array(
                'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                'runningSum' => 0,
            )
        );
        $expectedResponse = array(
            0 => $emptyDayReport + array('category' => array('id' => $catalogIds['1.1'])),
            1 => $emptyDayReport + array('category' => array('id' => $catalogIds['1.2']))
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByGroupsReport()
    {
        list($storeIds,, $catalogIds) = $this->createSales();

        $output = new NullOutput();
        $this->getGrossSalesReportService()->recalculateGrossSalesProductReport();
        $this->getGrossSalesReportService()->recalculateGrossSalesBySubCategories($output);
        $this->getGrossSalesReportService()->recalculateGrossSalesByCategories($output);
        $this->getGrossSalesReportService()->recalculateGrossSalesByGroups($output);

        $accessToken = $this->factory->authAsStoreManager($storeIds['1']);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeIds['1']}/reports/grossSalesByGroups",
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogIds['1'], '0.group.id', $response);
        Assert::assertJsonPathEquals($catalogIds['2'], '1.group.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['group'] = array('id' => $filteredResponse[0]['group']['id']);
        $filteredResponse[1]['group'] = array('id' => $filteredResponse[1]['group']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 408.09,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 708.91,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 1286.2,
                ),
                'group' => array('id' => $catalogIds['1'])
            ),
            1 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 0,
                ),
                'group' => array('id' => $catalogIds['2']),
            )
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
        $this->assertSame($expectedResponse, $filteredResponse);
    }

    public function testGrossSalesByGroupsEmpty()
    {
        list($storeIds,, $catalogIds) = $this->createSalesProducts();

        $accessToken = $this->factory->authAsStoreManager($storeIds['1']);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeIds['1']}/reports/grossSalesByGroups",
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($catalogIds['1'], '0.group.id', $response);
        Assert::assertJsonPathEquals($catalogIds['2'], '1.group.id', $response);

        $filteredResponse = $response;
        $filteredResponse[0]['group'] = array('id' => $filteredResponse[0]['group']['id']);
        $filteredResponse[1]['group'] = array('id' => $filteredResponse[1]['group']['id']);

        $expectedResponse = array(
            0 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 0,
                ),
                'group' => array('id' => $catalogIds['1'])
            ),
            1 => array(
                'today' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('10:00')),
                    'runningSum' => 0,
                ),
                'yesterday' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-1 day 10:00')),
                    'runningSum' => 0,
                ),
                'weekAgo' => array(
                    'dayHour' => date(DateTime::ISO8601, strtotime('-7 day 10:00')),
                    'runningSum' => 0,
                ),
                'group' => array('id' => $catalogIds['2'])
            )
        );
        $this->assertEquals($expectedResponse, $filteredResponse);
    }
}
