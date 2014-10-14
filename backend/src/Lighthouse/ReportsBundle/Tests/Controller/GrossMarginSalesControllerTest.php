<?php

namespace Lighthouse\ReportsBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;

use Lighthouse\CoreBundle\Test\Assert;
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
     * @return array|\string[]
     */
    protected function initInvoiceAndSales(Store $store, $productIds, $productOtherSubCategoryId)
    {
        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-10 days'))), $store->id)
                ->createInvoiceProduct($productIds['1'], 100, 90)
                ->createInvoiceProduct($productIds['2'], 100, 50)
                ->createInvoiceProduct($productIds['3'], 100, 100)
                ->createInvoiceProduct($productOtherSubCategoryId, 99, 77)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store, '8:01')
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

    public function testGrossMarginSalesByProduct()
    {
        $store = $this->factory()->store()->getStore();
        $subCategory = $this->factory()->catalog()->getSubCategory();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $otherSubCategory = $this->factory()->catalog()->getSubCategory('other sub category');
        $productOtherSubCategoryId = $this->createProduct('33', $otherSubCategory->id);

        $this->initInvoiceAndSales($store, $productIds, $productOtherSubCategoryId);


        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateGrossMarginSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            array('store' => $store->id)
        );

        $this->assertResponseCode(200);

        /*
         *  product['1']
            'grossSales' => 4500,
            'costOfGoods' => 2700,
            'grossMargin' => 1800,
            'quantity' => 30
        */
        $this->assertGrossMarginSalesReportByProduct($productIds['1'], 4500, 2700, 1800, 30, $response);

        /*
         *  product['2']
            'grossSales' => 3600,
            'costOfGoods' => 1800,
            'grossMargin' => 1800,
            'quantity' => 36
        */
        $this->assertGrossMarginSalesReportByProduct($productIds['2'], 3600, 1800, 1800, 36, $response);

        /*
         *  product['3']
            'grossSales' => 7540,
            'costOfGoods' => 5800,
            'grossMargin' => 1740,
            'quantity' => 58
        */
        $this->assertGrossMarginSalesReportByProduct($productIds['3'], 7540, 5800, 1740, 58, $response);
    }

    public function testGrossMarginSalesByProductWithPeriod()
    {
        $store = $this->factory()->store()->getStore();
        $subCategory = $this->factory()->catalog()->getSubCategory();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $otherSubCategory = $this->factory()->catalog()->getSubCategory("other sub category");
        $productOtherSubCategoryId = $this->createProduct('33', $otherSubCategory->id);

        $this->initInvoiceAndSales($store, $productIds, $productOtherSubCategoryId);

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateGrossMarginSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            array(
                'dateFrom' => date('c', strtotime('-4 day 00:00:00')),
                'dateTo' => date('c', strtotime('-1 day 00:00:00')),
                'store' => $store->id,
            )
        );

        $this->assertResponseCode(200);

        $this->assertGrossMarginSalesReportByProduct($productIds['1'], 3000, 1800, 1200, 20, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['2'], 2600, 1300, 1300, 26, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['3'], 4940, 3800, 1140, 38, $response);
    }

    public function assertGrossMarginSalesReportByProduct(
        $productId,
        $expectedGrossSales,
        $expectedCostOfGoods,
        $expectedGrossMargin,
        $expectedQuantity,
        array $response
    ) {
        $found = false;
        foreach ($response as $reportElement) {
            if ($reportElement['product']['id'] == $productId) {
                $found = true;
                $this->assertEquals($expectedGrossSales, $reportElement['grossSales']);
                $this->assertEquals($expectedCostOfGoods, $reportElement['costOfGoods']);
                $this->assertEquals($expectedGrossMargin, $reportElement['grossMargin']);
                $this->assertEquals($expectedQuantity, $reportElement['quantity']);
            }
        }

        $this->assertTrue($found, sprintf('Report for product %s, not found', $productId));
    }

    public function testGrossMarginSalesByProductForAllStores()
    {
        $store1 = $this->factory()->store()->getStore();
        $store2 = $this->factory()->store()->getStore("other store");
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $subCategory = $this->factory()->catalog()->getSubCategory();
        $otherSubCategory = $this->factory()->catalog()->getSubCategory("other sub category");
        $productOtherSubCategoryId = $this->createProduct('33', $otherSubCategory->id);

        $this->initInvoiceAndSales($store1, $productIds, $productOtherSubCategoryId);
        $this->initInvoiceAndSales($store2, $productIds, $productOtherSubCategoryId);

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateGrossMarginSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            array(
                'dateFrom' => date('c', strtotime('-4 day 00:00:00')),
                'dateTo' => date('c', strtotime('-1 day 00:00:00'))
            )
        );

        $this->assertResponseCode(200);

        $this->assertGrossMarginSalesReportByProduct($productIds['1'], 6000, 3600, 2400, 40, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['2'], 5200, 2600, 2600, 52, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['3'], 9880, 7600, 2280, 76, $response);
    }

    public function testGrossMarginSalesByProductEmptyReportsForAllStores()
    {
        $this->factory()->store()->getStore();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $subCategory = $this->factory()->catalog()->getSubCategory();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            array(
                'dateFrom' => date('c', strtotime('-4 day 00:00:00')),
                'dateTo' => date('c', strtotime('-1 day 00:00:00'))
            )
        );

        $this->assertResponseCode(200);

        $this->assertGrossMarginSalesReportByProduct($productIds['1'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['2'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['3'], 0, 0, 0, 0, $response);
    }

    public function testGrossMarginSalesByProductEmptyReportsForStore()
    {
        $store1 = $this->factory()->store()->getStore();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $subCategory = $this->factory()->catalog()->getSubCategory();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            array(
                'dateFrom' => date('c', strtotime('-4 day 00:00:00')),
                'dateTo' => date('c', strtotime('-1 day 00:00:00')),
                'store' => $store1->id,
            )
        );

        $this->assertResponseCode(200);

        $this->assertGrossMarginSalesReportByProduct($productIds['1'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['2'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['3'], 0, 0, 0, 0, $response);
    }

    public function testGrossMarginSalesByProductEmptyPeriod()
    {
        $store = $this->factory()->store()->getStore();
        $subCategory = $this->factory()->catalog()->getSubCategory();
        $productIds = $this->createProductsByNames(array('1', '2', '3'));
        $otherSubCategory = $this->factory()->catalog()->getSubCategory('other sub category');
        $productOtherSubCategoryId = $this->createProduct('33', $otherSubCategory->id);

        $this->initInvoiceAndSales($store, $productIds, $productOtherSubCategoryId);

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateGrossMarginSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            array(
                'dateFrom' => date('c', strtotime('-9 day 00:00:00')),
                'dateTo' => date('c', strtotime('-7 day 00:00:00')),
            )
        );

        $this->assertResponseCode(200);

        $this->assertGrossMarginSalesReportByProduct($productIds['1'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['2'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['3'], 0, 0, 0, 0, $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/reports/gross/catalog/groups/{$subCategory->id}/products",
            null,
            array(
                'dateFrom' => date('c', strtotime('+1 day 00:00:00')),
                'dateTo' => date('c', strtotime('+5 day 00:00:00')),
            )
        );

        $this->assertResponseCode(200);

        $this->assertGrossMarginSalesReportByProduct($productIds['1'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['2'], 0, 0, 0, 0, $response);
        $this->assertGrossMarginSalesReportByProduct($productIds['3'], 0, 0, 0, 0, $response);
    }

    public function testGrossMarginSalesByCatalogGroupEmptyReportsForAllStores()
    {
        $this->factory()->store()->getStore();
        $catalogGroup1 = $this->factory()->catalog()->getSubCategory('1');
        $catalogGroup2 = $this->factory()->catalog()->getSubCategory('2');
        $catalogGroup3 = $this->factory()->catalog()->getSubCategory('3');

        $this->createProductByName('1.1', $catalogGroup1->id);
        $this->createProductByName('1.2', $catalogGroup1->id);
        $this->createProductByName('2.0', $catalogGroup2->id);
        // catalog group 3 does not have products

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross/catalog/groups',
            null,
            array(
                'dateFrom' => date('c', strtotime('-4 day 00:00:00')),
                'dateTo' => date('c', strtotime('-1 day 00:00:00'))
            )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.subCategory.id', $response);
        $this->assertGrossMarginSalesReportByCatalogGroup($response, $catalogGroup1->id);
        $this->assertGrossMarginSalesReportByCatalogGroup($response, $catalogGroup2->id);
        $this->assertGrossMarginSalesReportByCatalogGroup($response, $catalogGroup3->id);
    }

    public function testGrossMarginSalesByCatalogGroupEmptyReportsForAllOneStore()
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $catalogGroup1 = $this->factory()->catalog()->getSubCategory('1');
        $catalogGroup2 = $this->factory()->catalog()->getSubCategory('2');
        $catalogGroup3 = $this->factory()->catalog()->getSubCategory('3');

        $this->createProductByName('1.1', $catalogGroup1->id);
        $this->createProductByName('1.2', $catalogGroup1->id);
        $this->createProductByName('2.0', $catalogGroup2->id);
        // catalog group 3 does not have products

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross/catalog/groups',
            null,
            array(
                'store' => $stores['1']->id,
                'dateFrom' => date('c', strtotime('-4 day 00:00:00')),
                'dateTo' => date('c', strtotime('-1 day 00:00:00'))
            )
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.subCategory.id', $response);
        $this->assertGrossMarginSalesReportByCatalogGroup($response, $catalogGroup1->id);
        $this->assertGrossMarginSalesReportByCatalogGroup($response, $catalogGroup2->id);
        $this->assertGrossMarginSalesReportByCatalogGroup($response, $catalogGroup3->id);
    }

    /**
     * @dataProvider grossMarginSalesByCatalogGroupReportsProvider
     *
     * @param string $storeName
     * @param string $dateFrom
     * @param string $dateTo
     * @param array $assertions
     */
    public function testGrossMarginSalesByCatalogGroupReports($storeName, $dateFrom, $dateTo, array $assertions)
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $catalogGroups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $productIds = array();
        $productIds['1'] = $this->createProductByName('1.1', $catalogGroups['1']->id);
        $productIds['2'] = $this->createProductByName('1.2', $catalogGroups['1']->id);
        $productIds['3'] = $this->createProductByName('1.3', $catalogGroups['1']->id);
        $productIds['4'] = $this->createProductByName('2.0', $catalogGroups['2']->id);

        $this->initInvoiceAndSales($stores['1'], $productIds, $productIds['4']);

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateGrossMarginSalesCatalogGroupReport();

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

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/reports/gross/catalog/groups',
            null,
            $query
        );

        $this->assertResponseCode(200);

        foreach ($assertions as $groupCatalogName => $assertion) {
            list(
                $expectedGrossSales,
                $expectedCostOfGoods,
                $expectedGrossMargin,
                $expectedQuantity
            ) = $assertion;
            $groupCatalogId = $catalogGroups[$groupCatalogName]->id;
            $this->assertGrossMarginSalesReportByCatalogGroup(
                $response,
                $groupCatalogId,
                $expectedGrossSales,
                $expectedCostOfGoods,
                $expectedGrossMargin,
                $expectedQuantity
            );
        }
    }

    /**
     * @return array
     */
    public function grossMarginSalesByCatalogGroupReportsProvider()
    {
        return array(
            'all stores, no dates' => array(
                null,
                null,
                null,
                array(
                    '1' => array(15640, 10300, 5340, 124),
                    '2' => array(13800, 10626, 3174, 138),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            '-1 to -4 days' => array(
                null,
                '-4 day 00:00:00',
                '-1 day 00:00:00',
                array(
                    '1' => array(10540, 6900, 3640, 84),
                    '2' => array(9200, 7084, 2116, 92),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            '-1 to -4 days store 1' => array(
                '1',
                '-4 day 00:00:00',
                '-1 day 00:00:00',
                array(
                    '1' => array(10540, 6900, 3640, 84),
                    '2' => array(9200, 7084, 2116, 92),
                    '3' => array(0, 0, 0, 0),
                )
            ),
            '-1 to -4 days store 2' => array(
                '2',
                '-4 day 00:00:00',
                '-1 day 00:00:00',
                array(
                    '1' => array(0, 0, 0, 0),
                    '2' => array(0, 0, 0, 0),
                    '3' => array(0, 0, 0, 0),
                )
            ),
        );
    }

    /**
     * @param array $response
     * @param string $catalogGroupId
     * @param float|int $expectedGrossSales
     * @param float|int $expectedCostOfGoods
     * @param float|int $expectedGrossMargin
     * @param float|int $expectedQuantity
     */
    public function assertGrossMarginSalesReportByCatalogGroup(
        array $response,
        $catalogGroupId,
        $expectedGrossSales = 0,
        $expectedCostOfGoods = 0,
        $expectedGrossMargin = 0,
        $expectedQuantity = 0
    ) {
        foreach ($response as $reportElement) {
            if ($reportElement['subCategory']['id'] == $catalogGroupId) {
                $this->assertSame($expectedGrossSales, $reportElement['grossSales']);
                $this->assertSame($expectedCostOfGoods, $reportElement['costOfGoods']);
                $this->assertSame($expectedGrossMargin, $reportElement['grossMargin']);
                $this->assertSame($expectedQuantity, $reportElement['quantity']);
                return;
            }
        }

        $this->fail(sprintf('Report for catalogGroup %s, not found', $catalogGroupId));
    }
}
