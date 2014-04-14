<?php

namespace Lighthouse\CoreBundle\Tests\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;

class CostOfGoodsTest extends WebTestCase
{
    public function testIndexRangeCreatedOnInvoiceConsecutiveInsert()
    {
        $productIds = $this->createProductsBySku(array('1', '2', '3'));

        $store1 = $this->factory->store()->getStoreId('701');

        $invoice11 = $this->createInvoice(array('acceptanceDate' => '2014-01-12 12:23:13'), $store1);
        $this->createInvoiceProduct($invoice11, $productIds['1'], 105.678, 16.36, $store1);
        $this->createInvoiceProduct($invoice11, $productIds['3'], 320, 178.34, $store1);

        $invoice12 = $this->createInvoice(array('acceptanceDate' => '2014-01-13 19:56:04'), $store1);
        $this->createInvoiceProduct($invoice12, $productIds['2'], 45.04, 189.67, $store1);
        $this->createInvoiceProduct($invoice12, $productIds['3'], 115.12, 176.51, $store1);

        $invoice13 = $this->createInvoice(array('acceptanceDate' => '2014-01-13 20:03:14'), $store1);
        $this->createInvoiceProduct($invoice13, $productIds['1'], 111.67, 201.15, $store1);
        $this->createInvoiceProduct($invoice13, $productIds['3'], 115, 176.51, $store1);

        $invoice14 = $this->createInvoice(array('acceptanceDate' => '2014-01-14 08:15:31'), $store1);
        $this->createInvoiceProduct($invoice14, $productIds['1'], 300.01, 201.15, $store1);

        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();

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

        $store1 = $this->factory->store()->getStoreId('701');

        $invoice11 = $this->createInvoice(array('acceptanceDate' => '2014-01-10 12:23:13'), $store1);
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

        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();

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

        $store = $this->factory->store()->getStoreId('701');

        $sale = $this->factory->createSale($store, '2014-01-11 13:45:09', 110.23);
        $this->factory->createSaleProduct(34.12, 3, $productId, $sale);
        $this->factory->createSaleProduct(34.12, 7, $productId, $sale);
        $this->factory->createSaleProduct(34.12, 1, $productId, $sale);
        $this->factory->flush();

        /* @var \Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();

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

    /**
     * @param float $start
     * @param float $end
     * @param array $expectedSkus
     * @dataProvider findInvoiceByRangeIndexProvider
     */
    public function testFindInvoiceByRangeIndex($start, $end, array $expectedSkus)
    {
        $productId = $this->createProduct('1');
        $store = $this->factory->store()->getStoreId('701');
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store, $productId);

        $invoiceId1 = $this->createInvoice(array('acceptanceDate' => '2014-01-12 12:23:12'), $store);
        $this->createInvoiceProduct($invoiceId1, $productId, 5, 10.09, $store);
        $invoiceId2 = $this->createInvoice(array('acceptanceDate' => '2014-01-12 13:23:12'), $store);
        $this->createInvoiceProduct($invoiceId2, $productId, 3, 10.09, $store);
        $invoiceId3 = $this->createInvoice(array('acceptanceDate' => '2014-01-12 14:23:12'), $store);
        $this->createInvoiceProduct($invoiceId3, $productId, 2, 10.09, $store);

        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceRepository = $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
        $numericFactory = $this->getContainer()->get('lighthouse.core.types.numeric.factory');
        $startIndex = $numericFactory->createQuantity($start);
        $endIndex = $numericFactory->createQuantity($end);
        $cursor = $trialBalanceRepository->findByIndexRange(
            InvoiceProduct::REASON_TYPE,
            $storeProductId,
            $startIndex,
            $endIndex
        );
        $this->assertEquals(count($expectedSkus), $cursor->count(true));
        foreach ($expectedSkus as $expectedSku) {
            /* @var TrialBalance $trialBalance */
            $trialBalance = $cursor->getNext();
            $this->assertNotNull($trialBalance);
            /* @var Invoice $invoice */
            $invoice = $trialBalance->reason->getReasonParent();
            $this->assertEquals($expectedSku, $invoice->number);
        }
    }

    /**
     * @return array
     */
    public function findInvoiceByRangeIndexProvider()
    {
        return array(
            '0,5 - exact range #1' => array(
                0,
                5,
                array(10001)
            ),
            '5,8 - exact range #2' => array(
                5,
                8,
                array(10002)
            ),
            '8,10 - exact range #3' => array(
                8,
                10,
                array(10003),
            ),
            '0,1 - start/inside #1' => array(
                0,
                1,
                array(10001)
            ),
            '1,3 - inside #1' => array(
                1,
                3,
                array(10001)
            ),
            '0,6' => array(
                0,
                6,
                array(10001, 10002)
            ),
            '4,6' => array(
                4,
                6,
                array(10001, 10002)
            ),
            '5,6' => array(
                5,
                6,
                array(10002)
            ),
            '6,8' => array(
                6,
                8,
                array(10002)
            ),
            '10,11' => array(
                10,
                11,
                array()
            ),
            '1,9' => array(
                1,
                9,
                array(10001, 10002, 10003)
            ),
            '0,11' => array(
                1,
                9,
                array(10001, 10002, 10003)
            ),
            '11,14' => array(
                11,
                14,
                array()
            )
        );
    }

    /**
     * @param float $start
     * @param float $end
     * @param string $expectedCostOfGoods
     * @dataProvider costOfGoodsCalculateByIndexRangeProvider
     */
    public function testCostOfGoodsCalculateByIndexRange($start, $end, $expectedCostOfGoods)
    {
        $productId = $this->createProduct('1');
        $store = $this->factory->store()->getStoreId('701');
        $storeProductRepository = $this->getContainer()->get('lighthouse.core.document.repository.store_product');
        $storeProductId = $storeProductRepository->getIdByStoreIdAndProductId($store, $productId);

        $invoiceId1 = $this->createInvoice(array('acceptanceDate' => '2014-01-12 12:23:12'), $store);
        $this->createInvoiceProduct($invoiceId1, $productId, 5, 11.09, $store);
        $invoiceId2 = $this->createInvoice(array('acceptanceDate' => '2014-01-12 13:23:12'), $store);
        $this->createInvoiceProduct($invoiceId2, $productId, 3, 12.13, $store);
        $invoiceId3 = $this->createInvoice(array('acceptanceDate' => '2014-01-12 14:23:12'), $store);
        $this->createInvoiceProduct($invoiceId3, $productId, 2, 10.09, $store);

        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();

        $numericFactory = $this->getContainer()->get('lighthouse.core.types.numeric.factory');
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $startIndex = $numericFactory->createQuantity($start);
        $endIndex = $numericFactory->createQuantity($end);
        $costOfGoods = $costOfGoodsCalculator->calculateByIndexRange($storeProductId, $startIndex, $endIndex);
        $this->assertSame($expectedCostOfGoods, $costOfGoods->toNumber());
    }

    /**
     * @return array
     */
    public function costOfGoodsCalculateByIndexRangeProvider()
    {
        return array(
            '0,5 - exact range #1' => array(
                0,
                5,
                55.45
            ),
            '5,8 - exact range #2' => array(
                5,
                8,
                36.39
            ),
            '8,10 - exact range #3' => array(
                8,
                10,
                20.18,
            ),
            '0,1 - start/inside #1' => array(
                0,
                1,
                11.09
            ),
            '1,3 - inside #1' => array(
                1,
                3,
                22.18
            ),
            '0,6' => array(
                0,
                6,
                67.58
            ),
            '4,6' => array(
                4,
                6,
                23.22
            ),
            '5,6' => array(
                5,
                6,
                12.13
            ),
            '6,8' => array(
                6,
                8,
                24.26
            ),
            '10,11' => array(
                10,
                11,
                10.09
            ),
            '1,9' => array(
                1,
                9,
                90.84
            ),
            '0,11' => array(
                1,
                9,
                90.84
            ),
            '11,14' => array(
                11,
                14,
                30.27
            )
        );
    }

    public function testCostOfGoodsCalculate()
    {
        $store = $this->factory->store()->getStoreId("1");
        $product1 = $this->createProduct("1");
        $this->createProduct("2");

        $invoice1 = $this->createInvoice(array(), $store);
        $this->createInvoiceProduct($invoice1, $product1, 1.345, 23.77, $store);
        $this->createInvoiceProduct($invoice1, $product1, 2.332, 0.1, $store);
        $this->createInvoiceProduct($invoice1, $product1, 3, 13.3, $store);
        $this->createInvoiceProduct($invoice1, $product1, 4.23, 14, $store);
        $invoice2 = $this->createInvoice(array(), $store);
        $this->createInvoiceProduct($invoice2, $product1, 5.7, 17.99, $store);
        // Total quantity = 16.607
        $this->factory->flush();

        $sale1 = $this->factory->createSale($store, "-1 hour", 10.533495);
        $saleProduct1 = $this->factory->createSaleProduct(2.333, 2.435, $product1, $sale1);
        $saleProduct2 = $this->factory->createSaleProduct(2.333, 1.32, $product1, $sale1);
        $saleProduct3 = $this->factory->createSaleProduct(2.333, 0.76, $product1, $sale1);

        $sale2 = $this->factory->createSale($store, "now", 6.2991);
        $saleProduct4 = $this->factory->createSaleProduct(2.333, 1, $product1, $sale2);
        $saleProduct5 = $this->factory->createSaleProduct(2.333, 1.7, $product1, $sale2);
        // Total quantity = 7.215

        $this->factory->flush();

        // Calculate CostOfGoods

        /* @var \Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceRepository = $this->getContainer()->get("lighthouse.core.document.repository.trial_balance");

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('32.08', $trialBalanceSaleProduct1->costOfGoods->toString());
        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('1.16', $trialBalanceSaleProduct2->costOfGoods->toString());
        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('10.11', $trialBalanceSaleProduct3->costOfGoods);
        $trialBalanceSaleProduct4 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct4->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('13.30', $trialBalanceSaleProduct4->costOfGoods->toString());
        $trialBalanceSaleProduct5 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId((string) $saleProduct5->id, SaleProduct::REASON_TYPE);
        $this->assertEquals('22.98', $trialBalanceSaleProduct5->costOfGoods->toString());
    }

    public function testCostOfGoodsCalculateAfterInsertOldReceipts()
    {
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        /** @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get("lighthouse.core.document.repository.trial_balance");

        $store = $this->factory->store()->getStoreId("1");
        $product = $this->createProduct('1');

        $invoice1 = $this->createInvoice(array(), $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 100, $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 150, $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 200, $store);


        $sale1 = $this->factory->createSale($store, "2014-01-10 12:23:12", 1500);
        $saleProduct1 = $this->factory->createSaleProduct(250, 6, $product, $sale1);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();


        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(650, $trialBalanceSaleProduct1->costOfGoods->toNumber());


        $saleBehindhand = $this->factory->createSale($store, "2014-01-09 12:23:12", 1750);
        $saleProductBehindhand = $this->factory->createSaleProduct(250, 7, $product, $saleBehindhand);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();


        $trialBalanceSaleProductBehindhand = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProductBehindhand->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(800, $trialBalanceSaleProductBehindhand->costOfGoods->toNumber());

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1050, $trialBalanceSaleProduct1->costOfGoods->toNumber());


        $saleBehindhand2 = $this->factory->createSale($store, "2014-01-09 16:23:12", 500);
        $saleProductBehindhand2 = $this->factory->createSaleProduct(250, 2, $product, $saleBehindhand2);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();


        $trialBalanceSaleProductBehindhand = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProductBehindhand->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(800, $trialBalanceSaleProductBehindhand->costOfGoods->toNumber());

        $trialBalanceSaleProductBehindhand2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProductBehindhand2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(300, $trialBalanceSaleProductBehindhand2->costOfGoods->toNumber());

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct1->costOfGoods->toNumber());
    }

    public function testCostOfGoodsCalculateDuplicateReceipt()
    {
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        /** @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get("lighthouse.core.document.repository.trial_balance");

        $store = $this->factory->store()->getStoreId("1");
        $product = $this->createProduct("1");

        $invoice1 = $this->createInvoice(array(), $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 100, $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 150, $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 200, $store);


        $sale1 = $this->factory->createSale($store, "2014-01-09 12:23:12", 1750);
        $saleProduct1 = $this->factory->createSaleProduct(250, 7, $product, $sale1);
        $this->factory->flush();

        $sale2 = $this->factory->createSale($store, "2014-01-09 16:23:12", 500);
        $saleProduct2 = $this->factory->createSaleProduct(250, 2, $product, $sale2);
        $this->factory->flush();

        $sale3 = $this->factory->createSale($store, "2014-01-10 12:23:12", 1500);
        $saleProduct3 = $this->factory->createSaleProduct(250, 6, $product, $sale3);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(800, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(300, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct3->costOfGoods->toNumber());


        $this->factory->deleteSale($sale1);
        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(200, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(750, $trialBalanceSaleProduct3->costOfGoods->toNumber());



        $sale1 = $this->factory->createSale($store, "2014-01-09 12:23:12", 1000);
        $saleProduct1 = $this->factory->createSaleProduct(250, 4, $product, $sale1);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(400, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(250, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1000, $trialBalanceSaleProduct3->costOfGoods->toNumber());


        $this->factory->deleteSale($sale2);
        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(400, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(850, $trialBalanceSaleProduct3->costOfGoods->toNumber());
    }

    public function testCostOfGoodsCalculateEditInvoice()
    {
        $this->markTestSkipped('Broken need to be fixed');
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        /** @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get("lighthouse.core.document.repository.trial_balance");

        $storeId = $this->factory->store()->getStoreId("1");
        $productId = $this->createProduct("1");
        $productOtherId = $this->createProduct("Other");

        $invoice1Id = $this->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $storeId);
        $invoiceProduct1Id = $this->createInvoiceProduct($invoice1Id, $productId, 5, 100, $storeId);
        $invoice2Id = $this->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $storeId);
        $invoiceProduct2Id = $this->createInvoiceProduct($invoice2Id, $productId, 5, 150, $storeId);
        $invoice3Id = $this->createInvoice(array('acceptanceDate' => '2014-01-03 12:56'), $storeId);
        $this->createInvoiceProduct($invoice3Id, $productId, 10, 200, $storeId);


        $sale1 = $this->factory->createSale($storeId, "2014-01-09 12:23:12", 1750);
        $saleProduct1 = $this->factory->createSaleProduct(250, 7, $productId, $sale1);
        $this->factory->flush();

        $sale2 = $this->factory->createSale($storeId, "2014-01-09 16:23:12", 500);
        $saleProduct2 = $this->factory->createSaleProduct(250, 2, $productId, $sale2);
        $this->factory->flush();

        $sale3 = $this->factory->createSale($storeId, "2014-01-10 12:23:12", 1500);
        $saleProduct3 = $this->factory->createSaleProduct(250, 6, $productId, $sale3);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(800, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(300, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct3->costOfGoods->toNumber());

        // Edit invoice product price and quantity
        $this->editInvoiceProduct($invoiceProduct1Id, $invoice1Id, $productId, 6, 50, $storeId);

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(450, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(300, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1100, $trialBalanceSaleProduct3->costOfGoods->toNumber());


        // Edit invoice product change product
        $this->editInvoiceProduct($invoiceProduct1Id, $invoice1Id, $productOtherId, 1, 11, $storeId);

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(400, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1200, $trialBalanceSaleProduct3->costOfGoods->toNumber());


        // Edit invoice product delete not first
        $this->editInvoiceProduct($invoiceProduct1Id, $invoice1Id, $productId, 5, 100, $storeId);
        $costOfGoodsCalculator->calculateUnprocessed();

        $this->factory()->clear();
        $this->factory()->invoice()->deleteInvoiceProduct($invoiceProduct2Id);
        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(900, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(400, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1200, $trialBalanceSaleProduct3->costOfGoods->toNumber());


        // Edit invoice product delete first
        $this->createInvoiceProduct($invoice2Id, $productId, 5, 150, $storeId);
        $costOfGoodsCalculator->calculateUnprocessed();

        $this->factory()->invoice()->deleteInvoiceProduct($invoiceProduct1Id);
        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(400, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1200, $trialBalanceSaleProduct3->costOfGoods->toNumber());
    }

    public function testCostOfGoodsCalculateEditInvoiceDate()
    {
        $this->markTestSkipped('Broken need to be fixed');
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        /** @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get("lighthouse.core.document.repository.trial_balance");

        $store = $this->factory->store()->getStoreId("1");
        $product = $this->createProduct("1");
        $this->createProduct("Other");


        $invoice1 = $this->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 100, $store);
        $invoice2 = $this->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $store);
        $this->createInvoiceProduct($invoice2, $product, 5, 150, $store);
        $invoice3 = $this->createInvoice(array('acceptanceDate' => '2014-01-03 12:56'), $store);
        $this->createInvoiceProduct($invoice3, $product, 10, 200, $store);


        $sale1 = $this->factory->createSale($store, "2014-01-09 12:23:12", 1750);
        $saleProduct1 = $this->factory->createSaleProduct(250, 7, $product, $sale1);
        $this->factory->flush();

        $sale2 = $this->factory->createSale($store, "2014-01-09 16:23:12", 500);
        $saleProduct2 = $this->factory->createSaleProduct(250, 2, $product, $sale2);
        $this->factory->flush();

        $sale3 = $this->factory->createSale($store, "2014-01-10 12:23:12", 1500);
        $saleProduct3 = $this->factory->createSaleProduct(250, 6, $product, $sale3);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(800, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(300, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct3->costOfGoods->toNumber());

        $this->factory()->clear();
        $this->editInvoice(array('acceptanceDate' => '2014-01-01 10:00'), $invoice2, $store);

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(950, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(200, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1100, $trialBalanceSaleProduct3->costOfGoods->toNumber());

        $this->factory()->clear();
        $this->editInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $invoice2, $store);

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(800, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $trialBalanceSaleProduct2 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct2->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(300, $trialBalanceSaleProduct2->costOfGoods->toNumber());

        $trialBalanceSaleProduct3 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct3->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct3->costOfGoods->toNumber());
    }

    public function testCostOfGoodsCalculateOutOfStock()
    {
        /* @var CostOfGoodsCalculator $costOfGoodsCalculator */
        $costOfGoodsCalculator = $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
        /** @var TrialBalanceRepository $trialBalanceRepository */
        $trialBalanceRepository = $this->getContainer()->get("lighthouse.core.document.repository.trial_balance");

        $store = $this->factory->store()->getStoreId("1");
        $product = $this->createProduct(array("purchasePrice" => 100));
        $this->createProduct("Other");

        $sale1 = $this->factory->createSale($store, "2014-01-09 12:23:12", 1750);
        $saleProduct1 = $this->factory->createSaleProduct(250, 7, $product, $sale1);
        $this->factory->flush();

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(700, $trialBalanceSaleProduct1->costOfGoods->toNumber());


        $invoice1 = $this->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $store);
        $this->createInvoiceProduct($invoice1, $product, 5, 150, $store);

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1050, $trialBalanceSaleProduct1->costOfGoods->toNumber());


        $invoice2 = $this->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $store);
        $this->createInvoiceProduct($invoice2, $product, 1, 200, $store);

        $costOfGoodsCalculator->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $trialBalanceRepository
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(1150, $trialBalanceSaleProduct1->costOfGoods->toNumber());
    }
}
