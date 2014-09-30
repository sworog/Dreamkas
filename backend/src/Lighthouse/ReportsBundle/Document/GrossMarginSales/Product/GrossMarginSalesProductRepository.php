<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Product;

use Doctrine\MongoDB\ArrayIterator;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use DateTime;

class GrossMarginSalesProductRepository extends DocumentRepository
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function setTrialBalanceRepository(TrialBalanceRepository $trialBalanceRepository)
    {
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    /**
     * @param NumericFactory $numericFactory
     */
    public function setNumericFactory(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param string $storeProductId
     * @param DateTime $day
     * @return string
     */
    public function getIdByStoreProductIdAndDay($storeProductId, $day)
    {
        return md5($storeProductId . ":" . $day->getTimestamp());
    }

    /**
     * @param array $storeProductIds
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return GrossMarginSalesProductReport[]|Cursor
     */
    public function findByStoreProductsAndPeriod(array $storeProductIds, DateTime $startDate, DateTime $endDate)
    {
        $startDate = new DateTimestamp($startDate);
        $endDate = new DateTimestamp($endDate);

        return $this->findBy(
            array(
                'product' => array('$in' => $storeProductIds),
                'day' => array(
                    '$gte' => $startDate->getMongoDate(),
                    '$lte' => $endDate->getMongoDate(),
                ),
            )
        );
    }

    /**
     * @param $storeProductId
     * @param DateTime $day
     * @return GrossMarginSalesProductReport|object
     */
    public function findByStoreProductAndDay($storeProductId, DateTime $day)
    {
        $reportId = $this->getIdByStoreProductIdAndDay($storeProductId, $day);
        return $this->find($reportId);
    }

    /**
     * @param int $batch
     * @return int
     */
    public function recalculate($batch = 5000)
    {
        $requireDatePeriod = new DatePeriod("-8 day 00:00", "+1 day 23:59:59");

        $results = $this->aggregateProductByDay($requireDatePeriod->getStartDate(), $requireDatePeriod->getEndDate());
        $count = 0;

        foreach ($results as $result) {
            $report = new GrossMarginSalesProductReport();
            $report->day = DateTimestamp::createFromParts(
                $result['_id']['year'],
                $result['_id']['month'],
                $result['_id']['day']
            );
            $report->id = $this->getIdByStoreProductIdAndDay($result['_id']['storeProduct'], $report->day);
            $report->costOfGoods = $this->numericFactory->createMoneyFromCount($result['costOfGoodsSum']);
            $report->quantity = $this->numericFactory->createQuantityFromCount($result['quantitySum']);
            $report->grossSales = $this->numericFactory->createMoneyFromCount($result['grossSales']);
            $report->grossMargin = $this->numericFactory->createMoneyFromCount($result['grossMargin']);
            $report->product = $this->dm->getReference(StoreProduct::getClassName(), $result['_id']['storeProduct']);

            $this->dm->persist($report);
            $count++;

            if ($count % $batch == 0) {
                $this->dm->flush();
            }
        }

        $this->dm->flush();

        return $count;
    }

    /**
     * @param DateTimestamp $startDate
     * @param DateTimestamp $endDate
     * @return ArrayIterator
     */
    protected function aggregateProductByDay(DateTimestamp $startDate, DateTimestamp $endDate)
    {
        $ops = array(
            array(
                '$match' => array(
                    'createdDate.date' => array(
                        '$gte' => $startDate->getMongoDate(),
                        '$lt' => $endDate->getMongoDate(),
                    ),
                    'reason.$ref' => SaleProduct::TYPE,
                ),
            ),
            array(
                '$sort' => array(
                    'createdDate.date' => self::SORT_ASC,
                )
            ),
            array(
                '$project' => array(
                    'totalPrice' => 1,
                    'costOfGoods' => 1,
                    'quantity' => 1,
                    'storeProduct' => 1,
                    'year' => '$createdDate.year',
                    'month' => '$createdDate.month',
                    'day' => '$createdDate.day'
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array(
                        'storeProduct' => '$storeProduct',
                        'year' => '$year',
                        'month' => '$month',
                        'day' => '$day',
                    ),
                    'grossSales' => array(
                        '$sum' => '$totalPrice'
                    ),
                    'costOfGoodsSum' => array(
                        '$sum' => '$costOfGoods'
                    ),
                    'quantitySum' => array(
                        '$sum' => '$quantity.count'
                    )
                ),
            ),
            array(
                '$project' => array(
                    '_id' => 1,
                    'grossSales' => 1,
                    'costOfGoodsSum' => 1,
                    'quantitySum' => 1,
                    'grossMargin' => array(
                        '$subtract' => array('$grossSales', '$costOfGoodsSum')
                    )
                )
            )
        );

        return $this->trialBalanceRepository->aggregate($ops);
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Cursor
     */
    public function findByStartEndDate(DateTime $startDate, DateTime $endDate)
    {
        $startDate = new DateTimestamp($startDate);
        $endDate = new DateTimestamp($endDate);

        return $this->findBy(array(
            'day' => array(
                '$gte' => $startDate->getMongoDate(),
                '$lt' => $endDate->getMongoDate(),
            ),
        ));
    }
}
