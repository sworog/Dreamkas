<?php

namespace Lighthouse\CoreBundle\Tests\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsCalculator;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class CostOfGoodsTest extends WebTestCase
{
    public function testIndexRangeCreatedOnInvoiceConsecutiveInsert()
    {
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $store = $this->factory()->store()->getStore('701');

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-12 12:23:13'), $store->id)
                ->createInvoiceProduct($productIds['1'], 105.678, 16.36)
                ->createInvoiceProduct($productIds['3'], 320, 178.34)
            ->persist()
                ->createInvoice(array('acceptanceDate' => '2014-01-13 19:56:04'), $store->id)
                ->createInvoiceProduct($productIds['2'], 45.04, 189.67)
                ->createInvoiceProduct($productIds['3'], 115.12, 176.51)
            ->persist()
                ->createInvoice(array('acceptanceDate' => '2014-01-13 20:03:14'), $store->id)
                ->createInvoiceProduct($productIds['1'], 111.67, 201.15)
                ->createInvoiceProduct($productIds['3'], 115, 176.51)
            ->persist()
                ->createInvoice(array('acceptanceDate' => '2014-01-14 08:15:31'), $store->id)
                ->createInvoiceProduct($productIds['1'], 300.01, 201.15)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        foreach ($productIds as $productId) {
            $storeProductId = $this->getStoreProductRepository()->getIdByStoreIdAndProductId($store->id, $productId);
            $prevEndIndex = '0.000';
            $trailBalances = $this->getTrialBalanceRepository()->findByStoreProductIdAndReasonType(
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
        $productIds = $this->createProductsByNames(array('1', '2', '3'));

        $store = $this->factory()->store()->getStore('701');

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-10 12:23:13'), $store->id)
                ->createInvoiceProduct($productIds['1'], 16.36, 10.09)
                ->createInvoiceProduct($productIds['2'], 10.067, 29.56)
                ->createInvoiceProduct($productIds['3'], 20, 30.05)
            ->flush();

        $sale1 = $this->factory()->createSale($store->id, '2014-01-11 13:45:09', 110.23);
        $this->factory()->createSaleProduct(12.11, 9.102, $productIds['1'], $sale1);
        $this->factory()->createSaleProduct(34.12, 7, $productIds['3'], $sale1);
        $this->factory()->createSaleProduct(34.12, 1, $productIds['3'], $sale1);
        $this->factory()->flush();

        $sale2 = $this->factory()->createSale($store->id, '2014-01-12 15:45:09', 110.23);
        $this->factory()->createSaleProduct(34.99, 2.056, $productIds['2'], $sale2);
        $this->factory()->createSaleProduct(35.15, 6, $productIds['3'], $sale2);
        $this->factory()->flush();

        $sale3 = $this->factory()->createSale($store->id, '2014-01-12 15:45:10', 110.23);
        $this->factory()->createSaleProduct(11.49, 4.56, $productIds['1'], $sale3);
        $this->factory()->createSaleProduct(35.15, 2, $productIds['3'], $sale3);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $countAssertions = array(
            $productIds['1'] => 2,
            $productIds['2'] => 1,
            $productIds['3'] => 4,
        );

        foreach ($productIds as $productId) {
            $storeProductId = $this->getStoreProductRepository()->getIdByStoreIdAndProductId($store->id, $productId);
            $prevEndIndex = '0.000';
            $trailBalances = $this->getTrialBalanceRepository()->findByStoreProductIdAndReasonType(
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

        $store = $this->factory()->store()->getStoreId('701');

        $sale = $this->factory()->createSale($store, '2014-01-11 13:45:09', 110.23);
        $this->factory()->createSaleProduct(34.12, 3, $productId, $sale);
        $this->factory()->createSaleProduct(34.12, 7, $productId, $sale);
        $this->factory()->createSaleProduct(34.12, 1, $productId, $sale);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $storeProductId = $this->getStoreProductRepository()->getIdByStoreIdAndProductId($store, $productId);
        $prevEndIndex = '0.000';
        $trailBalances = $this->getTrialBalanceRepository()->findByStoreProductIdAndReasonType(
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
        $store = $this->factory()->store()->getStore('701');

        $storeProductId = $this->getStoreProductRepository()->getIdByStoreIdAndProductId($store->id, $productId);

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-12 12:23:12'), $store->id)
                ->createInvoiceProduct($productId, 5, 10.09)
            ->persist()
                ->createInvoice(array('acceptanceDate' => '2014-01-12 13:23:12'), $store->id)
                ->createInvoiceProduct($productId, 3, 10.09)
            ->persist()
                ->createInvoice(array('acceptanceDate' => '2014-01-12 14:23:12'), $store->id)
                ->createInvoiceProduct($productId, 2, 10.09)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $startIndex = $this->getNumericFactory()->createQuantity($start);
        $endIndex = $this->getNumericFactory()->createQuantity($end);

        $cursor = $this->getTrialBalanceRepository()->findByIndexRange(
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
        $store = $this->factory()->store()->getStore('701');
        $storeProductId = $this->getStoreProductRepository()->getIdByStoreIdAndProductId($store->id, $productId);

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-12 12:23:12'), $store->id)
                ->createInvoiceProduct($productId, 5, 11.09)
            ->persist()
                ->createInvoice(array('acceptanceDate' => '2014-01-12 13:23:12'), $store->id)
                ->createInvoiceProduct($productId, 3, 12.13)
            ->persist()
                ->createInvoice(array('acceptanceDate' => '2014-01-12 14:23:12'), $store->id)
                ->createInvoiceProduct($productId, 2, 10.09)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $startIndex = $this->getNumericFactory()->createQuantity($start);
        $endIndex = $this->getNumericFactory()->createQuantity($end);
        $costOfGoods = $this->getCostOfGoodsCalculator()->calculateByIndexRange(
            $storeProductId,
            $startIndex,
            $endIndex
        );
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
        $store = $this->factory()->store()->getStore('1');
        $productId = $this->createProduct('1');
        $this->createProduct('2');

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId, 1.345, 23.77)
                ->createInvoiceProduct($productId, 2.332, 0.1)
                ->createInvoiceProduct($productId, 3, 13.3)
                ->createInvoiceProduct($productId, 4.23, 14)
            ->persist()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId, 5.7, 17.99)
            ->flush();
        // Total quantity = 16.607

        $sale1 = $this->factory()->createSale($store->id, '-1 hour', 10.533495);
        $saleProduct1 = $this->factory()->createSaleProduct(2.333, 2.435, $productId, $sale1);
        $saleProduct2 = $this->factory()->createSaleProduct(2.333, 1.32, $productId, $sale1);
        $saleProduct3 = $this->factory()->createSaleProduct(2.333, 0.76, $productId, $sale1);

        $sale2 = $this->factory()->createSale($store->id, 'now', 6.2991);
        $saleProduct4 = $this->factory()->createSaleProduct(2.333, 1, $productId, $sale2);
        $saleProduct5 = $this->factory()->createSaleProduct(2.333, 1.7, $productId, $sale2);
        // Total quantity = 7.215

        $this->factory()->flush();

        // Calculate CostOfGoods

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '32.08');
        $this->assertCostOfGood($saleProduct2, '1.16');
        $this->assertCostOfGood($saleProduct3, '10.11');
        $this->assertCostOfGood($saleProduct4, '13.30');
        $this->assertCostOfGood($saleProduct5, '22.98');
    }

    public function testCostOfGoodsCalculateAfterInsertOldReceipts()
    {
        $store = $this->factory()->store()->getStore('1');
        $productId = $this->createProduct('1');

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId, 5, 100)
                ->createInvoiceProduct($productId, 5, 150)
                ->createInvoiceProduct($productId, 5, 200)
            ->flush();

        $sale1 = $this->factory()->createSale($store->id, '2014-01-10 12:23:12', 1500);
        $saleProduct1 = $this->factory()->createSaleProduct(250, 6, $productId, $sale1);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '650.00');

        $saleBehindhand = $this->factory()->createSale($store->id, '2014-01-09 12:23:12', 1750);
        $saleProductBehindhand1 = $this->factory()->createSaleProduct(250, 7, $productId, $saleBehindhand);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProductBehindhand1, '800.00');
        $this->assertCostOfGood($saleProduct1, '1050.00');

        $saleBehindhand2 = $this->factory()->createSale($store->id, '2014-01-09 16:23:12', 500);
        $saleProductBehindhand2 = $this->factory()->createSaleProduct(250, 2, $productId, $saleBehindhand2);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProductBehindhand1, '800.00');
        $this->assertCostOfGood($saleProductBehindhand2, '300.00');
        $this->assertCostOfGood($saleProduct1, '1150.00');
    }

    public function testCostOfGoodsCalculateDuplicateReceipt()
    {
        $store = $this->factory()->store()->getStore('1');
        $productId = $this->createProduct('1');

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($productId, 5, 100)
                ->createInvoiceProduct($productId, 5, 150)
                ->createInvoiceProduct($productId, 5, 200)
            ->flush();


        $sale1 = $this->factory()->createSale($store->id, '2014-01-09 12:23:12', 1750);
        $saleProduct1 = $this->factory()->createSaleProduct(250, 7, $productId, $sale1);
        $this->factory()->flush();

        $sale2 = $this->factory()->createSale($store->id, '2014-01-09 16:23:12', 500);
        $saleProduct2 = $this->factory()->createSaleProduct(250, 2, $productId, $sale2);
        $this->factory()->flush();

        $sale3 = $this->factory()->createSale($store->id, '2014-01-10 12:23:12', 1500);
        $saleProduct3 = $this->factory()->createSaleProduct(250, 6, $productId, $sale3);
        $this->factory()->flush();
        $this->factory()->clear();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '800.00');
        $this->assertCostOfGood($saleProduct2, '300.00');
        $this->assertCostOfGood($saleProduct3, '1150.00');


        $this->factory()->deleteSale($sale1);
        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct2, '200.00');
        $this->assertCostOfGood($saleProduct3, '750.00');


        $sale4 = $this->factory()->createSale($store->id, '2014-01-09 12:23:12', 1000);
        $saleProduct4 = $this->factory()->createSaleProduct(250, 4, $productId, $sale4);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct4, '400.00');
        $this->assertCostOfGood($saleProduct2, '250.00');
        $this->assertCostOfGood($saleProduct3, '1000.00');

        $this->factory()->deleteSale($sale2);
        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct4, '400.00');
        $this->assertCostOfGood($saleProduct3, '850.00');
    }

    public function testCostOfGoodsCalculateEditInvoice()
    {
        $store = $this->factory()->store()->getStore('1');
        $productId = $this->createProduct('1');
        $productOtherId = $this->createProduct('Other');

        $invoice1 = $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $store->id)
                ->createInvoiceProduct($productId, 5, 100)
                ->createInvoiceProduct($productOtherId, 1, 1)
            ->flush();

        $invoice2 = $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $store->id)
                ->createInvoiceProduct($productId, 5, 150)
                ->createInvoiceProduct($productOtherId, 1, 1)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:56'), $store->id)
                ->createInvoiceProduct($productId, 10, 200)
            ->flush();

        $sale1 = $this->factory()->createSale($store->id, '2014-01-09 12:23:12', 1750);
        $saleProduct1 = $this->factory()->createSaleProduct(250, 7, $productId, $sale1);
        $this->factory()->flush();

        $sale2 = $this->factory()->createSale($store->id, '2014-01-09 16:23:12', 500);
        $saleProduct2 = $this->factory()->createSaleProduct(250, 2, $productId, $sale2);
        $this->factory()->flush();

        $sale3 = $this->factory()->createSale($store->id, '2014-01-10 12:23:12', 1500);
        $saleProduct3 = $this->factory()->createSaleProduct(250, 6, $productId, $sale3);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '800.00');
        $this->assertCostOfGood($saleProduct2, '300.00');
        $this->assertCostOfGood($saleProduct3, '1150.00');

        // Edit invoice product price and quantity
        $this->factory()
            ->invoice()
                ->editInvoice($invoice1->id)
                ->editInvoiceProduct(0, $productId, 6, 50)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '450.00');
        $this->assertCostOfGood($saleProduct2, '300.00');
        $this->assertCostOfGood($saleProduct3, '1100.00');


        // Edit invoice product change product
        $this->factory()
            ->invoice()
                ->editInvoice($invoice1->id)
                ->editInvoiceProduct(0, $productOtherId, 1, 11)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '1150.00');
        $this->assertCostOfGood($saleProduct2, '400.00');
        $this->assertCostOfGood($saleProduct3, '1200.00');


        // Edit invoice product delete not first
        $this->factory()
            ->clear()
            ->invoice()
                ->editInvoice($invoice1->id)
                ->editInvoiceProduct(0, $productId, 5, 100)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '800.00');
        $this->assertCostOfGood($saleProduct2, '300.00');
        $this->assertCostOfGood($saleProduct3, '1150.00');

        $this->factory()
            ->clear()
            ->invoice()
                ->editInvoice($invoice2->id)
                ->deleteInvoiceProduct(0)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '900.00');
        $this->assertCostOfGood($saleProduct2, '400.00');
        $this->assertCostOfGood($saleProduct3, '1200.00');


        // Edit invoice product delete first
        $this->factory()
            ->clear()
            ->invoice()
                ->editInvoice($invoice2->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '800.00');
        $this->assertCostOfGood($saleProduct2, '300.00');
        $this->assertCostOfGood($saleProduct3, '1150.00');

        $this->factory()
            ->clear()
            ->invoice()
                ->editInvoice($invoice1->id)
                ->deleteInvoiceProduct(0)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '1150.00');
        $this->assertCostOfGood($saleProduct2, '400.00');
        $this->assertCostOfGood($saleProduct3, '1200.00');
    }

    public function testCostOfGoodsCalculateEditInvoiceDate()
    {
        $store = $this->factory()->store()->getStore('1');
        $productId = $this->createProduct('1');
        $this->createProduct('Other');

        $storeProductId = $this->getStoreProductRepository()->getIdByStoreIdAndProductId($store->id, $productId);

        $invoice1 = $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $store->id)
                ->createInvoiceProduct($productId, 5, 100, $store->id)
            ->flush();
        $invoice2 = $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $store->id)
                ->createInvoiceProduct($productId, 5, 150, $store->id)
            ->flush();

        $invoice3 = $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-03 12:56'), $store->id)
                ->createInvoiceProduct($productId, 10, 200, $store->id)
            ->flush();

        $sale1 = $this->factory()->createSale($store->id, '2014-01-09 12:23:12', 1750);
        $saleProduct1 = $this->factory()->createSaleProduct(250, 7, $productId, $sale1);
        $this->factory()->flush();

        $sale2 = $this->factory()->createSale($store->id, '2014-01-09 16:23:12', 500);
        $saleProduct2 = $this->factory()->createSaleProduct(250, 2, $productId, $sale2);
        $this->factory()->flush();

        $sale3 = $this->factory()->createSale($store->id, '2014-01-10 12:23:12', 1500);
        $saleProduct3 = $this->factory()->createSaleProduct(250, 6, $productId, $sale3);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '800.00');
        $this->assertCostOfGood($saleProduct2, '300.00');
        $this->assertCostOfGood($saleProduct3, '1150.00');

        $this->factory()
            ->clear()
            ->invoice()
                ->editInvoice($invoice2->id, array('acceptanceDate' => '2014-01-01 10:00'))
            ->flush();

        /*
        $this->assertStoreProductTrialBalance(
            $storeProductId,
            InvoiceProduct::REASON_TYPE,
            array(
                array(
                    'reasonId' => $invoice2->products[0]->id,
                    'status' => TrialBalance::PROCESSING_STATUS_UNPROCESSED
                ),
                array(
                    'reasonId' => $invoice1->products[0]->id,
                    'status' => TrialBalance::PROCESSING_STATUS_OK
                ),
                array(
                    'reasonId' => $invoice3->products[0]->id,
                    'status' => TrialBalance::PROCESSING_STATUS_OK
                ),
            )
        );
        */

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '950.00');
        $this->assertCostOfGood($saleProduct2, '200.00');
        $this->assertCostOfGood($saleProduct3, '1100.00');

        $this->factory()
            ->clear()
            ->invoice()
                ->editInvoice($invoice2->id, array('acceptanceDate' => '2014-01-02 12:56'))
            ->flush();

        $this->assertStoreProductTrialBalance(
            $storeProductId,
            InvoiceProduct::REASON_TYPE,
            array(
                array(
                    'reasonId' => $invoice1->products[0]->id,
                    'status' => TrialBalance::PROCESSING_STATUS_UNPROCESSED
                ),
                array(
                    'reasonId' => $invoice2->products[0]->id,
                    'status' => TrialBalance::PROCESSING_STATUS_UNPROCESSED
                ),
                array(
                    'reasonId' => $invoice3->products[0]->id,
                    'status' => TrialBalance::PROCESSING_STATUS_OK
                ),
            )
        );

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '800.00');
        $this->assertCostOfGood($saleProduct2, '300.00');
        $this->assertCostOfGood($saleProduct3, '1150.00');
    }

    public function testCostOfGoodsCalculateOutOfStock()
    {
        $store = $this->factory()->store()->getStore('1');
        $productId = $this->createProduct(array('purchasePrice' => 100));
        $this->createProduct('Other');

        $sale1 = $this->factory()->createSale($store->id, '2014-01-09 12:23:12', 1750);
        $saleProduct1 = $this->factory()->createSaleProduct(250, 7, $productId, $sale1);
        $this->factory()->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $trialBalanceSaleProduct1 = $this->getTrialBalanceRepository()
            ->findOneByReasonTypeReasonId($saleProduct1->id, SaleProduct::REASON_TYPE);
        $this->assertEquals(700, $trialBalanceSaleProduct1->costOfGoods->toNumber());

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-01 12:56'), $store->id)
                ->createInvoiceProduct($productId, 5, 150)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '1050.00');

        $this->factory()
            ->invoice()
                ->createInvoice(array('acceptanceDate' => '2014-01-02 12:56'), $store->id)
                ->createInvoiceProduct($productId, 1, 200)
            ->flush();

        $this->getCostOfGoodsCalculator()->calculateUnprocessed();

        $this->assertCostOfGood($saleProduct1, '1150.00');
    }

    /**
     * @param string $storeProductId
     * @param string $reasonType
     * @param array $expectations
     */
    protected function assertStoreProductTrialBalance($storeProductId, $reasonType, array $expectations)
    {
        $trialBalances = $this->getTrialBalanceRepository()->findByStoreProductIdAndReasonType(
            $storeProductId,
            $reasonType
        );
        $this->assertCount(count($expectations), $trialBalances);

        foreach ($expectations as $expected) {
            /* @var TrialBalance $trialBalance */
            $trialBalance = $trialBalances->getNext();
            $this->assertEquals($expected['reasonId'], $trialBalance->reason->getReasonId());
            $this->assertEquals($expected['status'], $trialBalance->processingStatus, 'Status does not match');
        }
    }

    /**
     * @param SaleProduct $saleProduct
     * @param string $expectedCostOfGood
     */
    protected function assertCostOfGood(SaleProduct $saleProduct, $expectedCostOfGood)
    {
        $trialBalance = $this->getTrialBalanceRepository()->findOneByReason($saleProduct);
        $this->assertSame($expectedCostOfGood, $trialBalance->costOfGoods->toString());
    }

    /**
     * @return TrialBalanceRepository
     */
    protected function getTrialBalanceRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.trial_balance');
    }

    /**
     * @return CostOfGoodsCalculator
     */
    protected function getCostOfGoodsCalculator()
    {
        return $this->getContainer()->get('lighthouse.core.document.trial_balance.calculator');
    }

    /**
     * @return StoreProductRepository
     */
    protected function getStoreProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.store_product');
    }

    /**
     * @return NumericFactory
     */
    protected function getNumericFactory()
    {
        return $this->getContainer()->get('lighthouse.core.types.numeric.factory');
    }
}
