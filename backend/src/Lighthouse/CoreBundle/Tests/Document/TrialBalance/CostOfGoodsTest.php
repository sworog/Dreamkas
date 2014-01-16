<?php

namespace Lighthouse\CoreBundle\Tests\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Test\WebTestCase;

class CostOfGoodsTest extends WebTestCase
{
    public function testIndexRangeCreatedOnInvoiceConsecutiveInsert()
    {
        $productIds = $this->createProductsBySku(array('1', '2', '3'));

        $store1 = $this->createStore('701');

        $invoice11 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-12 12:23:13'), $store1);
        $this->createInvoiceProduct($invoice11, $productIds['1'], 105.678, 16.36, $store1);
        $this->createInvoiceProduct($invoice11, $productIds['3'], 320, 178.34, $store1);

        $invoice12 = $this->createInvoice(array('sku' => 2, 'acceptanceDate' => '2014-01-13 19:56:04'), $store1);
        $this->createInvoiceProduct($invoice12, $productIds['2'], 45.04, 189.67, $store1);
        $this->createInvoiceProduct($invoice12, $productIds['3'], 115.12, 176.51, $store1);

        $invoice13 = $this->createInvoice(array('sku' => 3, 'acceptanceDate' => '2014-01-13 20:03:14'), $store1);
        $this->createInvoiceProduct($invoice13, $productIds['1'], 111.67, 201.15, $store1);
        $this->createInvoiceProduct($invoice13, $productIds['3'], 115, 176.51, $store1);

        $invoice14 = $this->createInvoice(array('sku' => 4, 'acceptanceDate' => '2014-01-14 08:15:31'), $store1);
        $this->createInvoiceProduct($invoice14, $productIds['1'], 300.01, 201.15, $store1);

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        foreach ($productIds as $productId) {
            $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store1, $productId);
            $prevEndIndex = '0.000';
            $trailBalances = $trialBalanceRepository->findByStoreProductIdAndReasonType(
                $storeProductId,
                InvoiceProduct::REASON_TYPE
            );
            foreach ($trailBalances as $trailBalance) {
                $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Quantity', $trailBalance->startIndex);
                $this->assertSame($prevEndIndex, $trailBalance->startIndex->toString());
                $prevEndIndex = $trailBalance->endIndex->toString();
                $this->assertNotSame('0.000', $prevEndIndex);
            }
        }
    }

    public function testIndexRangeCreatedOnSaleConsecutiveInsert()
    {
        $productIds = $this->createProductsBySku(array('1', '2', '3'));

        $store1 = $this->createStore('701');

        $invoice11 = $this->createInvoice(array('sku' => 1, 'acceptanceDate' => '2014-01-10 12:23:13'), $store1);
        $this->createInvoiceProduct($invoice11, $productIds['1'], 16.36, 10.09, $store1);
        $this->createInvoiceProduct($invoice11, $productIds['2'], 10.067, 29.56, $store1);
        $this->createInvoiceProduct($invoice11, $productIds['3'], 20, 30.05, $store1);

        $sale1 = $this->factory->createSale($store1, '2014-01-11 13:45:09', 110.23);
        $this->factory->createSaleProduct(12.11, 9.102, $productIds['1'], $sale1);
        $this->factory->createSaleProduct(34.12, 7, $productIds['3'], $sale1);
        $this->factory->createSaleProduct(34.12, 1, $productIds['3'], $sale1);
        $this->factory->flush();

        $sale2 = $this->factory->createSale($store1, '2014-01-12 15:45:09', 110.23);
        $this->factory->createSaleProduct(34.99, 2.056, $productIds['2'], $sale2);
        $this->factory->createSaleProduct(35.15, 6, $productIds['3'], $sale2);
        $this->factory->flush();

        $sale3 = $this->factory->createSale($store1, '2014-01-12 15:45:10', 110.23);
        $this->factory->createSaleProduct(11.49, 4.56, $productIds['1'], $sale3);
        $this->factory->createSaleProduct(35.15, 2, $productIds['3'], $sale3);
        $this->factory->flush();

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        $countAssertions = array(
            $productIds['1'] => 2,
            $productIds['2'] => 1,
            $productIds['3'] => 4,
        );

        foreach ($productIds as $productId) {
            $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store1, $productId);
            $prevEndIndex = '0.000';
            $trailBalances = $trialBalanceRepository->findByStoreProductIdAndReasonType(
                $storeProductId,
                SaleProduct::REASON_TYPE
            );
            $this->assertEquals($countAssertions[$productId], $trailBalances->count(true));
            foreach ($trailBalances as $trailBalance) {
                $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Quantity', $trailBalance->startIndex);
                $this->assertSame($prevEndIndex, $trailBalance->startIndex->toString());
                $prevEndIndex = $trailBalance->endIndex->toString();
                $this->assertNotSame('0.000', $prevEndIndex);
            }
        }
    }

    public function testIndexRangeCreatedOnSaleInsertManyPositionsInOneSale()
    {
        $productId = $this->createProduct('1');

        $store = $this->createStore('701');

        $sale = $this->factory->createSale($store, '2014-01-11 13:45:09', 110.23);
        $this->factory->createSaleProduct(34.12, 3, $productId, $sale);
        $this->factory->createSaleProduct(34.12, 7, $productId, $sale);
        $this->factory->createSaleProduct(34.12, 1, $productId, $sale);
        $this->factory->flush();

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');

        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');

        $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store, $productId);
        $prevEndIndex = '0.000';
        $trailBalances = $trialBalanceRepository->findByStoreProductIdAndReasonType(
            $storeProductId,
            SaleProduct::REASON_TYPE
        );
        $this->assertEquals(3, $trailBalances->count(true));
        foreach ($trailBalances as $trailBalance) {
            $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Quantity', $trailBalance->startIndex);
            $this->assertSame($prevEndIndex, $trailBalance->startIndex->toString());
            $prevEndIndex = $trailBalance->endIndex->toString();
            $this->assertNotSame('0.000', $prevEndIndex);
        }
    }
}
