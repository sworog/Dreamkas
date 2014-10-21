<?php

namespace Lighthouse\ReportsBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;

class GrossMarginSalesControllerTest extends WebTestCase
{
    /**
     * @return GrossMarginSalesReportManager
     */
    protected function getGrossMarginSalesReportManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin_sales.manager');
    }

    /**
     * @return GrossMarginManager
     */
    protected function getGrossMarginManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
    }

    /**
     * @param Store $store
     * @param array $productIds
     * @param $productOtherSubCategoryId
     * @param Store $otherStore
     * @return array
     */
    protected function initInvoiceAndSales(Store $store, $productIds, $productOtherSubCategoryId, Store $otherStore)
    {
        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-10 days'))), $store->id)
                ->createInvoiceProduct($productIds['1'], 80, 90)
                ->createInvoiceProduct($productIds['2'], 100, 50)
                ->createInvoiceProduct($productIds['3'], 100, 100)
                ->createInvoiceProduct($productOtherSubCategoryId, 99, 77)
            ->persist()
                ->createInvoice(array('date' => date('c', strtotime('-4 days'))), $otherStore->id)
                ->createInvoiceProduct($productIds['1'], 20, 80)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store, '0:00:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 7, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
                ->createReceiptProduct($productOtherSubCategoryId, 23, 100)
            ->persist()
                ->createSale($store, '-1 days 10:01')
                ->createReceiptProduct($productIds['1'], 7, 150)
                ->createReceiptProduct($productIds['2'], 5, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
                ->createReceiptProduct($productOtherSubCategoryId, 23, 100)
            ->persist()
                ->createSale($store, '-2 days 8:01')
                ->createReceiptProduct($productIds['1'], 3, 150)
                ->createReceiptProduct($productIds['2'], 5, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
                ->createReceiptProduct($productOtherSubCategoryId, 23, 100)
            ->persist()
                ->createSale($store, '-3 days 10:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 7, 100)
                ->createReceiptProduct($productIds['3'], 8, 130)
                ->createReceiptProduct($productOtherSubCategoryId, 23, 100)
            ->persist()
                ->createSale($otherStore, '-3 days 12:56')
                ->createReceiptProduct($productIds['1'], 6, 140)
            ->persist()
                ->createSale($store, '-4 days 9:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 9, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
                ->createReceiptProduct($productOtherSubCategoryId, 23, 100)
            ->persist()
                ->createSale($store, '-5 days 10:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 3, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
                ->createReceiptProduct($productOtherSubCategoryId, 23, 100)
            ->flush();

        return $productIds;
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param string $storeName
     * @param array $stores
     * @return array
     */
    protected function getFilterQuery($dateFrom, $dateTo, $storeName = null, array $stores = array())
    {
        $query = array();
        if (null !== $storeName) {
            $query['store'] = $stores[$storeName]->id;
        }
        if (null !== $dateFrom) {
            $query['dateFrom'] = date('c', strtotime($dateFrom));
        }
        if (null !== $dateTo) {
            $query['dateTo'] = date('c', strtotime($dateTo));
        }
        return $query;
    }

    /**
     * @dataProvider productGrossReportProvider
     *
     * @param string $storeName
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $assertions
     */
    public function testProductGrossReport($storeName, $dateFrom, $dateTo, array $assertions)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));
        $subCategory = $this->factory()->catalog()->getSubCategory();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $otherSubCategory = $this->factory()->catalog()->getSubCategory('other sub category');
        $productOtherSubCategoryId = $this->createProduct('33', $otherSubCategory->id);

        $this->initInvoiceAndSales($stores['1'], $productIds, $productOtherSubCategoryId, $stores['2']);

        $this->runRecalculateCommand();

        $query = $this->getFilterQuery($dateFrom, $dateTo, $storeName, $stores);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            $query
        );

        $this->assertResponseCode(200);

        foreach ($assertions as $productName => $expectedValues) {
            $productId = $productIds[$productName];
            $this->assertProductGrossReport($response, $productId, $expectedValues);
        }
    }

    /**
     * @return array
     */
    public function productGrossReportProvider()
    {
        return array(
            '1 store, no dates' => array(
                '1',
                null,
                null,
                array(
                    '1' => array(4500, 2700, 1800, 30),
                    '2' => array(3600, 1800, 1800, 36),
                    '3' => array(7540, 5800, 1740, 58),
                )
            ),
            '1 store, -4 days to yesterday' => array(
                '1',
                '-4 day 00:00:00',
                '00:00:00',
                array(
                    '1' => array(3000, 1800, 1200, 20),
                    '2' => array(2600, 1300, 1300, 26),
                    '3' => array(4940, 3800, 1140, 38),
                )
            ),
            'all stores, -4 days to yesterday' => array(
                '1',
                '-4 day 00:00:00',
                '00:00:00',
                array(
                    '1' => array(3000, 1800, 1200, 20),
                    '2' => array(2600, 1300, 1300, 26),
                    '3' => array(4940, 3800, 1140, 38),
                )
            ),
            'empty report: all stores, -9 days to -8 days' => array(
                null,
                '-9 day 00:00:00',
                '-7 day 00:00:00',
                array(
                    '1' => array(0, 0, 0, 0),
                    '2' => array(0, 0, 0, 0),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'empty report: all stores, +1 day to +5 days' => array(
                null,
                '+1 day 00:00:00',
                '+6 day 00:00:00',
                array(
                    '1' => array(0, 0, 0, 0),
                    '2' => array(0, 0, 0, 0),
                    '3' => array(0, 0, 0, 0),
                )
            ),
        );
    }

    /**
     * @dataProvider grossReportWithStoreEmptyProvider
     *
     * @param string $storeName
     * @param string $dateFrom
     * @param string $dateTo
     */
    public function testProductGrossReportEmpty($storeName, $dateFrom, $dateTo)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $subCategory = $this->factory()->catalog()->getSubCategory();

        $query = $this->getFilterQuery($dateFrom, $dateTo, $storeName, $stores);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            $query
        );

        $this->assertResponseCode(200);

        $this->assertProductGrossReport($response, $productIds['1'], array(0, 0, 0, 0));
        $this->assertProductGrossReport($response, $productIds['2'], array(0, 0, 0, 0));
        $this->assertProductGrossReport($response, $productIds['3'], array(0, 0, 0, 0));
    }

    /**
     * @return array
     */
    public function grossReportWithStoreEmptyProvider()
    {
        return array(
            '-4 days to -1 day' => array(
                null,
                '-4 day 00:00:00',
                '-1 day 00:00:00',
            ),
            'store 1, -4 days to -1 day' => array(
                '1',
                '-4 day 00:00:00',
                '-1 day 00:00:00',
            ),
            'all stores, no dates' => array(
                null,
                null,
                null
            )
        );
    }

    /**
     * @return array
     */
    public function grossReportEmptyProvider()
    {
        return array(
            '-4 days to -1 day' => array(
                '-4 day 00:00:00',
                '-1 day 00:00:00',
            ),
            'from -4 days' => array(
                '-4 day 00:00:00',
                null,
            ),
            'to -1 day' => array(
                null,
                '-1 day 00:00:00',
            ),
            'no dates' => array(
                null,
                null
            )
        );
    }

    /**
     * @dataProvider grossReportWithStoreEmptyProvider
     * @param $storeName
     * @param $dateFrom
     * @param $dateTo
     */
    public function testCatalogGroupGrossReportEmpty($storeName, $dateFrom, $dateTo)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $catalogGroups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $this->createProductByName('1.1', $catalogGroups['1']->id);
        $this->createProductByName('1.2', $catalogGroups['1']->id);
        $this->createProductByName('2.0', $catalogGroups['2']->id);
        // catalog group 3 does not have products

        $query = $this->getFilterQuery($dateFrom, $dateTo, $storeName, $stores);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross/catalog/groups',
            null,
            $query
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.subCategory.id', $response);
        foreach ($catalogGroups as $catalogGroup) {
            $this->assertCatalogGroupGrossReport($response, $catalogGroup->id, array(0, 0, 0, 0));
        }
    }

    /**
     * @dataProvider catalogGroupGrossReportProvider
     *
     * @param string $storeName
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $assertions
     */
    public function testCatalogGroupGrossReport($storeName, $dateFrom, $dateTo, array $assertions)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $catalogGroups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $productIds = array();
        $productIds['1'] = $this->createProductByName('1.1', $catalogGroups['1']->id);
        $productIds['2'] = $this->createProductByName('1.2', $catalogGroups['1']->id);
        $productIds['3'] = $this->createProductByName('1.3', $catalogGroups['1']->id);
        $productIds['4'] = $this->createProductByName('2.0', $catalogGroups['2']->id);

        $this->initInvoiceAndSales($stores['1'], $productIds, $productIds['4'], $stores['2']);

        $this->runRecalculateCommand();

        $query = $this->getFilterQuery($dateFrom, $dateTo, $storeName, $stores);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross/catalog/groups',
            null,
            $query
        );

        $this->assertResponseCode(200);

        foreach ($assertions as $groupCatalogName => $expectedValues) {
            $groupCatalogId = $catalogGroups[$groupCatalogName]->id;
            $this->assertCatalogGroupGrossReport($response, $groupCatalogId, $expectedValues);
        }
    }

    /**
     * @return array
     */
    public function catalogGroupGrossReportProvider()
    {
        return array(
            'all stores, no dates' => array(
                null,
                null,
                null,
                array(
                    '1' => array(16480, 10780, 5700, 130),
                    '2' => array(13800, 10626, 3174, 138),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'yesterday to -4 days' => array(
                null,
                '-4 day 00:00:00',
                '00:00:00',
                array(
                    '1' => array(11380, 7380, 4000, 90),
                    '2' => array(9200, 7084, 2116, 92),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'yesterday to -4 days store 1' => array(
                '1',
                '-4 day 00:00:00',
                '00:00:00',
                array(
                    '1' => array(10540, 6900, 3640, 84),
                    '2' => array(9200, 7084, 2116, 92),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'yesterday to -4 days store 2' => array(
                '2',
                '-4 day 00:00:00',
                '00:00:00',
                array(
                    '1' => array(840, 480, 360, 6),
                    '2' => array(0, 0, 0, 0),
                    '3' => array(0, 0, 0, 0),
                )
            ),
        );
    }

    /**
     * @dataProvider grossReportEmptyProvider
     * @param string $dateFrom
     * @param string $dateTo
     */
    public function testStoreGrossReportEmpty($dateFrom, $dateTo)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $catalogGroups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $this->createProductByName('1.1', $catalogGroups['1']->id);
        $this->createProductByName('1.2', $catalogGroups['1']->id);
        $this->createProductByName('2.0', $catalogGroups['2']->id);
        // catalog group 3 does not have products

        $query = $this->getFilterQuery($dateFrom, $dateTo);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross/stores',
            null,
            $query
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.store.id', $response);
        foreach ($stores as $store) {
            $this->assertStoreGrossReport($response, $store->id, array(0, 0, 0, 0));
        }
    }

    /**
     * @dataProvider storeGrossReportProvider
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $assertions
     */
    public function testStoreGrossReport($dateFrom, $dateTo, array $assertions)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $catalogGroups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $productIds = array();
        $productIds['1'] = $this->createProductByName('1.1', $catalogGroups['1']->id);
        $productIds['2'] = $this->createProductByName('1.2', $catalogGroups['1']->id);
        $productIds['3'] = $this->createProductByName('1.3', $catalogGroups['1']->id);
        $productIds['4'] = $this->createProductByName('2.0', $catalogGroups['2']->id);

        $this->initInvoiceAndSales($stores['1'], $productIds, $productIds['4'], $stores['2']);

        $this->runRecalculateCommand();

        $query = $this->getFilterQuery($dateFrom, $dateTo);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross/stores',
            null,
            $query
        );

        $this->assertResponseCode(200);

        foreach ($assertions as $storeName => $expectedValues) {
            $storeId = $stores[$storeName]->id;
            $this->assertStoreGrossReport($response, $storeId, $expectedValues);
        }
    }

    /**
     * @return array
     */
    public function storeGrossReportProvider()
    {
        return array(
            'no dates' => array(
                null,
                null,
                array(
                    '1' => array(29440, 20926, 8514, 262),
                    '2' => array(840, 480, 360, 6),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'yesterday to -4 days' => array(
                '-4 day 00:00:00',
                '00:00:00',
                array(
                    '1' => array(19740, 13984, 5756, 176),
                    '2' => array(840, 480, 360, 6),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'from -4 days' => array(
                '-4 day 00:00:00',
                null,
                array(
                    '1' => array(24790, 17555, 7235, 221),
                    '2' => array(840, 480, 360, 6),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'without today' => array(
                null,
                '00:00:00',
                array(
                    '1' => array(24390, 17355, 7035, 217),
                    '2' => array(840, 480, 360, 6),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            'yesterday' => array(
                '-1 day 00:00:00',
                '-1 day 23:59:59',
                array(
                    '1' => array(5150, 3651, 1499, 45),
                    '2' => array(0, 0, 0, 0),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            '-3 days 23:59:59' => array(
                '-3 day 00:00:00',
                '-3 day 23:59:59',
                array(
                    '1' => array(4790, 3371, 1419, 43),
                    '2' => array(840, 480, 360, 6),
                    '3' => array(0, 0, 0, 0)
                )
            ),
            '-3 days 00:00:00' => array(
                '-3 day 00:00:00',
                '-2 day 00:00:00',
                array(
                    '1' => array(4790, 3371, 1419, 43),
                    '2' => array(840, 480, 360, 6),
                    '3' => array(0, 0, 0, 0)
                )
            )
        );
    }

    /**
     * @dataProvider networkGrossReportProvider
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $expectedValues
     */
    public function testNetworkGrossReport($dateFrom, $dateTo, array $expectedValues)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $catalogGroups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $productIds = array();
        $productIds['1'] = $this->createProductByName('1.1', $catalogGroups['1']->id);
        $productIds['2'] = $this->createProductByName('1.2', $catalogGroups['1']->id);
        $productIds['3'] = $this->createProductByName('1.3', $catalogGroups['1']->id);
        $productIds['4'] = $this->createProductByName('2.0', $catalogGroups['2']->id);

        $this->initInvoiceAndSales($stores['1'], $productIds, $productIds['4'], $stores['2']);

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateNetworkReport();

        $query = $this->getFilterQuery($dateFrom, $dateTo);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross',
            null,
            $query
        );

        $this->assertResponseCode(200);

        $this->assertReportValues($expectedValues, $response);
    }

    /**
     * @return array
     */
    public function networkGrossReportProvider()
    {
        return array(
            'no dates' => array(
                null,
                null,
                array(30280, 21406, 8874, 268)
            ),
            '-1 to -4 days' => array(
                '-4 day 00:00:00',
                '00:00:00',
                array(20580, 14464, 6116, 182)
            ),
            'from -4 days' => array(
                '-4 day 00:00:00',
                null,
                array(25630, 18035, 7595, 227)
            ),
            'to -1 days' => array(
                null,
                '00:00:00',
                array(25230, 17835, 7395,223)
            ),
            'yesterday' => array(
                '-1 day 00:00:00',
                '-1 day 23:59:59',
                array(5150, 3651, 1499, 45)
            ),
            '-3 days 23:59' => array(
                '-3 day 00:00:00',
                '-3 day 23:59:59',
                array(5630, 3851, 1779, 49)
            ),
            '-3 days 00:00' => array(
                '-3 day 00:00:00',
                '-2 day 00:00:00',
                array(5630, 3851, 1779, 49)
            )
        );
    }

    /**
     * @dataProvider grossReportEmptyProvider
     *
     * @param string $dateFrom
     * @param string $dateTo
     */
    public function testNetworkGrossReportEmpty($dateFrom, $dateTo)
    {
        $this->factory()->store()->getStores(array('1', '2', '3'));
        $this->createProductsByNames(array('1', '2', '3'));
        $this->factory()->catalog()->getSubCategory();

        $query = $this->getFilterQuery($dateFrom, $dateTo);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross',
            null,
            $query
        );

        $this->assertResponseCode(200);

        $this->assertReportValues(array(0, 0, 0, 0), $response);
    }

    /**
     * @param array $response
     * @param string $productId
     * @param array $expectedValues
     */
    public function assertProductGrossReport(
        array $response,
        $productId,
        array $expectedValues
    ) {
        foreach ($response as $reportElement) {
            if ($reportElement['product']['id'] == $productId) {
                $this->assertReportValues($expectedValues, $reportElement);
                return;
            }
        }

        $this->fail(sprintf('Report for product %s, not found', $productId));
    }

    /**
     * @param array $response
     * @param string $catalogGroupId
     * @param array $expectedValues
     */
    public function assertCatalogGroupGrossReport(
        array $response,
        $catalogGroupId,
        array $expectedValues
    ) {
        foreach ($response as $reportElement) {
            if ($reportElement['subCategory']['id'] == $catalogGroupId) {
                $this->assertReportValues($expectedValues, $reportElement);
                return;
            }
        }

        $this->fail(sprintf('Report for catalogGroup %s, not found', $catalogGroupId));
    }

    /**
     * @param array $response
     * @param string $storeId
     * @param array $expectedValues
     */
    public function assertStoreGrossReport(
        array $response,
        $storeId,
        $expectedValues
    ) {
        foreach ($response as $reportElement) {
            if ($reportElement['store']['id'] == $storeId) {
                $this->assertReportValues($expectedValues, $reportElement);
                return;
            }
        }

        $this->fail(sprintf('Report for store %s, not found', $storeId));
    }

    /**
     * @param array $expectedValues
     * @param $reportElement
     */
    public function assertReportValues(array $expectedValues, $reportElement)
    {
        $this->assertSame($expectedValues[0], $reportElement['grossSales']);
        $this->assertSame($expectedValues[1], $reportElement['costOfGoods']);
        $this->assertSame($expectedValues[2], $reportElement['grossMargin']);
        $this->assertSame($expectedValues[3], $reportElement['quantity']);
    }

    /**
     * @return ApplicationTester
     */
    protected function runRecalculateCommand()
    {
        return $this->createConsoleTester(false, true)->runCommand('lighthouse:reports:recalculate');
    }
}
