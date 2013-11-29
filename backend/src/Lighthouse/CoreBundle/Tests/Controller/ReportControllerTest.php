<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Service\StoreGrossSalesReportService;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use DateTime;

class ReportControllerTest extends WebTestCase
{
    public function testGetReportsByTime()
    {
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);

        $product1Id = $this->createProduct('1');
        $product2Id = $this->createProduct('2');
        $product3Id = $this->createProduct('3');

        $sales = array(
            array(
                'storeId' => $storeId,
                'createDate' => "8:01",
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
                'createDate' => "9:01",
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
                'createDate' => "10:01",
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
                'createDate' => "11:01",
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

            array(
                'storeId' => $storeId,
                'createDate' => "-7 days 8:01",
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
                'createDate' => "-7 days 9:01",
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
                'createDate' => "-7 days 10:01",
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

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/report/grosssales',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("10:00")),
                    'value' => "1207.06",
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 10:00")),
                    'value' => "1207.06",
                    'diff' => "0.00",
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 23:59:59")),
                    'value' => "1810.59",
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 10:00")),
                    'value' => "1309.06",
                    'diff' => "8.45"
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 23:59:59")),
                    'value' => "1912.59",
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    public function testGetReportsByTimeEmptyReports()
    {
        $storeManager = $this->createUser('storeManager', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore();
        $this->factory->linkStoreManagers($storeManager->id, $storeId);

        $accessToken = $this->auth($storeManager, 'password');
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/report/grosssales',
            null,
            array('time' => date('c', strtotime("10:35:47")))
        );

        $this->assertResponseCode(200);

        $expected = array(
            'today' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("10:00")),
                    'value' => "0.00",
                )
            ),
            'yesterday' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 10:00")),
                    'value' => "0.00",
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-1 day 23:59:59")),
                    'value' => "0.00",
                ),
            ),
            'weekAgo' => array(
                'now' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 10:00")),
                    'value' => "0.00",
                ),
                'dayEnd' => array(
                    'date' => date(DateTime::ISO8601, strtotime("-7 day 23:59:59")),
                    'value' => "0.00",
                ),
            ),
        );

        $this->assertEquals($expected, $response);
    }

    /**
     * @return StoreGrossSalesReportService
     */
    public function getGrossSalesReportService()
    {
        return $this->getContainer()->get('lighthouse.core.service.store.report.gross_sales');
    }
}
