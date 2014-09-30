<?php

namespace Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
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
     * @return array|\string[]
     */
    protected function initInvoiceAndSales(Store $store)
    {
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-10 days'))))
                ->createInvoiceProduct($productIds[1], 100, 90)
                ->createInvoiceProduct($productIds[2], 100, 50)
                ->createInvoiceProduct($productIds[3], 100, 100)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($store, '8:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 7, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
            ->persist()
                ->createSale($store, '-1 days 10:01')
                ->createReceiptProduct($productIds['1'], 7, 150)
                ->createReceiptProduct($productIds['2'], 5, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
            ->persist()
                ->createSale($store, '-2 days 8:01')
                ->createReceiptProduct($productIds['1'], 3, 150)
                ->createReceiptProduct($productIds['2'], 5, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
            ->persist()
                ->createSale($store, '-3 days 10:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 7, 100)
                ->createReceiptProduct($productIds['3'], 8, 130)
            ->persist()
                ->createSale($store, '-4 days 9:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 9, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
            ->persist()
                ->createSale($store, '-5 days 10:01')
                ->createReceiptProduct($productIds['1'], 5, 150)
                ->createReceiptProduct($productIds['2'], 3, 100)
                ->createReceiptProduct($productIds['3'], 10, 130)
            ->flush();

        return $productIds;
    }

    public function testGrossMarginSalesByProduct()
    {
        $store = $this->factory()->store()->getStore();

        $productIds = $this->initInvoiceAndSales($store);

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateGrossMarginSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossMarginSalesByProduct"
        );

        $this->assertResponseCode(200);

        /*
         *  product['1']
            'grossSales' => 4500,
            'costOfGoods' => 2700,
            'grossMargin' => 1800,
            'quantity' => 30
        */
        $this->assertGrossMarginSalesReportByProduct(
            $productIds['1'],
            4500,
            2700,
            1800,
            30,
            $response
        );

        /*
         *  product['2']
            'grossSales' => 3600,
            'costOfGoods' => 1800,
            'grossMargin' => 1800,
            'quantity' => 36
        */
        $this->assertGrossMarginSalesReportByProduct(
            $productIds['2'],
            3600,
            1800,
            1800,
            36,
            $response
        );

        /*
         *  product['3']
            'grossSales' => 7540,
            'costOfGoods' => 5800,
            'grossMargin' => 1740,
            'quantity' => 58
        */
        $this->assertGrossMarginSalesReportByProduct(
            $productIds['3'],
            7540,
            5800,
            1740,
            58,
            $response
        );
    }

    public function testGrossMarginSalesByProductWithPeriod()
    {
        $store = $this->factory()->store()->getStore();

        $productIds = $this->initInvoiceAndSales($store);

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();
        $this->getGrossMarginSalesReportManager()->recalculateGrossMarginSalesProductReport();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/reports/grossMarginSalesByProduct",
            null,
            array(
                'startDate' => date('c', strtotime('-4 day 00:00:00')),
                'endDate' => date('c', strtotime('-1 day 00:00:00'))
            )
        );

        $this->assertResponseCode(200);

        $this->assertGrossMarginSalesReportByProduct(
            $productIds['1'],
            3000,
            1800,
            1200,
            20,
            $response
        );

        $this->assertGrossMarginSalesReportByProduct(
            $productIds['2'],
            2600,
            1300,
            1300,
            26,
            $response
        );

        $this->assertGrossMarginSalesReportByProduct(
            $productIds['3'],
            4940,
            3800,
            1140,
            38,
            $response
        );
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
            if ($reportElement['storeProduct']['product']['id'] == $productId) {
                $found = true;
                $this->assertEquals($expectedGrossSales, $reportElement['grossSales']);
                $this->assertEquals($expectedCostOfGoods, $reportElement['costOfGoods']);
                $this->assertEquals($expectedGrossMargin, $reportElement['grossMargin']);
                $this->assertEquals($expectedQuantity, $reportElement['quantity']);
            }
        }

        $this->assertTrue($found, printf('Report for product %s, not found', $productId));
    }
}
