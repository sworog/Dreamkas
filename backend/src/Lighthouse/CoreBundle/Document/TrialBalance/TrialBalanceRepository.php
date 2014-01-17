<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\Query\Expr;
use Doctrine\ODM\MongoDB\Query\Query;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use MongoId;
use MongoCode;

class TrialBalanceRepository extends DocumentRepository
{
    const MAX_STORE_GROSS_SALES_REPORT_AGGREGATION = 150000;

    /**
     * @param $storeProductId
     * @return Cursor|TrialBalance[]
     */
    public function findByStoreProduct($storeProductId)
    {
        return $this->findBy(
            array('storeProduct' => $storeProductId),
            array('createdDate' => self::SORT_ASC, '_id' => self::SORT_ASC)
        );
    }

    /**
     * @param string $storeProductId
     * @param string $reasonType
     * @return Cursor|TrialBalance[]
     */
    public function findByStoreProductIdAndReasonType($storeProductId, $reasonType)
    {
        $criteria = array(
            'storeProduct' => $storeProductId,
            'reason.$ref' => $reasonType
        );
        $sort = array(
            'createdDate' => self::SORT_ASC,
            '_id' => self::SORT_ASC
        );
        return $this->findBy($criteria, $sort);
    }

    /**
     * @param Reasonable $reason
     * @return TrialBalance
     */
    public function findOneByReason(Reasonable $reason)
    {
        $criteria = array(
            'reason.$id' => new MongoId($reason->getReasonId()),
            'reason.$ref' => $reason->getReasonType(),
        );
        return $this->findOneBy($criteria);
    }

    /**
     * @param TrialBalance $trialBalance
     * @return TrialBalance
     */
    public function findOnePrevious(TrialBalance $trialBalance)
    {
        $criteria = array(
            'reason.$ref' => $trialBalance->reason->getReasonType(),
            'storeProduct' => $trialBalance->storeProduct->id,
            'createdDate' => array('$lt' => $trialBalance->createdDate)
        );
        $sort = array(
            'createdDate' => self::SORT_DESC,
            '_id' => self::SORT_DESC
        );
        return $this->findOneBy($criteria, $sort);
    }

    /**
     * @param Reasonable[] $reasons
     * @return TrialBalance[]|Cursor
     */
    public function findByReasons($reasons)
    {
        $reasonTypes = array();
        foreach ($reasons as $reason) {
            $reasonTypes[$reason->getReasonType()][] = new MongoId($reason->getReasonId());
        }

        $query = $this->createQueryBuilder()->find();

        foreach ($reasonTypes as $reasonType => $reasonIds) {
            /* @var Expr $reasonQuery */
            $reasonQuery = $query->expr();
            $reasonQuery->field('reason.$id')->in($reasonIds);
            $reasonQuery->field('reason.$ref')->equals($reasonType);

            $query->addOr($reasonQuery->getQuery());
        }

        /* @var Cursor $result */
        $cursor = $query->getQuery()->execute();

        return $cursor;
    }

    /**
     * @param StoreProduct $storeProduct
     * @param InvoiceProduct $invoiceProduct
     * @return TrialBalance
     */
    public function findOneByStoreProduct(StoreProduct $storeProduct, InvoiceProduct $invoiceProduct = null)
    {
        $criteria = array('storeProduct' => $storeProduct->id);
        if (null !== $invoiceProduct) {
            $criteria['reason.$id'] = array('$ne' => new MongoId($invoiceProduct->id));
            //$criteria['reason.$ref'] = array('$ne' => 'InvoiceProduct');
        }
        // Ugly hack to force document refresh
        $hints = array(Query::HINT_REFRESH => true);
        $sort = array(
            'createdDate' => self::SORT_DESC,
            '_id' => self::SORT_DESC,
        );
        return $this->uow->getDocumentPersister($this->documentName)->load($criteria, null, $hints, 0, $sort);
    }

    /**
     * @param StoreProduct $storeProduct
     * @return TrialBalance
     */
    public function findOneReasonInvoiceProductByProduct(StoreProduct $storeProduct)
    {
        $criteria = array('storeProduct' => $storeProduct->id);
        $criteria['reason.$ref'] = InvoiceProduct::REASON_TYPE;
        // Ugly hack to force document refresh
        $hints = array(Query::HINT_REFRESH => true);
        $sort = array(
            'createdDate' => self::SORT_DESC,
            '_id' => self::SORT_DESC,
        );
        return $this->uow->getDocumentPersister($this->documentName)->load($criteria, null, $hints, 0, $sort);
    }

    /**
     * @return array
     */
    public function calculateAveragePurchasePrice()
    {
        if ($this->isCollectionEmpty()) {
            return array();
        }

        $datePeriod = new DatePeriod("-30 day 00:00", "00:00");

        $query = $this
            ->createQueryBuilder()
            ->field('createdDate')->gte($datePeriod->getStartDate()->getMongoDate())
            ->field('createdDate')->lt($datePeriod->getEndDate()->getMongoDate())
            ->field('reason.$ref')->equals(InvoiceProduct::REASON_TYPE)
            ->sort(array('storeProduct' => 1))
            ->map(
                new MongoCode(
                    "function() {
                        emit(
                            this.storeProduct,
                            {
                                totalPrice: this.totalPrice,
                                quantity: this.quantity.count
                            }
                        )
                    }"
                )
            )
            ->reduce(
                new MongoCode(
                    "function(storeProductId, obj) {
                        var reducedObj = {totalPrice: 0, quantity: 0}
                        for (var item in obj) {
                            reducedObj.totalPrice += obj[item].totalPrice;
                            reducedObj.quantity += obj[item].quantity;
                        }
                        return reducedObj;
                    }"
                )
            )
//            ->finalize(
//                new MongoCode(
//                    "function(storeProductId, obj) {
//                        if (obj.quantity > 0) {
//                            obj.averagePrice = obj.totalPrice / obj.quantity;
//                        } else {
//                            obj.averagePrice = null;
//                        }
//                        return obj;
//                    }"
//                )
//            )
            ->out(array('inline' => true));

        return $query->getQuery()->execute();
    }

    /**
     * @return array
     */
    public function calculateDailyAverageSalesAggregate()
    {
        $datePeriod = new DatePeriod("-30 day 00:00", "00:00");
        $days = $datePeriod->diff()->days;
        $collection = $this->getDocumentManager()->getDocumentCollection($this->getClassName());

        $ops = array(
            array(
                '$match' => array(
                    'createdDate' => array(
                        '$gte' => $datePeriod->getStartDate()->getMongoDate(),
                        '$lt' => $datePeriod->getEndDate()->getMongoDate(),
                    ),
                    'reason.$ref' => SaleProduct::REASON_TYPE
                ),
            ),
            array(
                '$sort' => array(
                    'storeProduct' => 1,
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$storeProduct',
                    'total' => array('$sum' => '$quantity.count')
                )
            ),
//            array(
//                '$project' => array(
//                    'value' => array(
//                        'dailyAverageSales' => array('$divide' => array('$total', $days))
//                    )
//                )
//            )
        );
        $result = $collection->getMongoCollection()->aggregate($ops);
        $results = array(
            'results' => $result['result'],
            'days' => $days
        );
        return $results;
    }

    /**
     * @return array
     */
    public function calculateDailyAverageSales()
    {
        if ($this->isCollectionEmpty()) {
            return array();
        }

        $datePeriod = new DatePeriod("-30 day 00:00", "00:00");
        $days = $datePeriod->diff()->days;
        $query = $this
            ->createQueryBuilder()
            ->field('createdDate')->gt($datePeriod->getStartDate()->getMongoDate())
            ->field('createdDate')->lt($datePeriod->getEndDate()->getMongoDate())
            ->field('reason.$ref')->equals(SaleProduct::REASON_TYPE)
            ->sort(array('storeProduct' => 1))
            ->map(
                new MongoCode(
                    "function() {
                        emit(
                            this.storeProduct,
                            {
                                quantity: this.quantity.count
                            }
                        )
                    }"
                )
            )
            ->reduce(
                new MongoCode(
                    "function (storeProductId, obj) {
                        var reducedObj = {quantity: 0}
                        for (var item in obj) {
                            reducedObj.quantity += obj[item].quantity;
                        }
                        return reducedObj;
                    }"
                )
            )
            ->finalize(
                new MongoCode(
                    sprintf(
                        "function(storeProductId, obj) {
                            if (obj.quantity > 0) {
                                obj.dailyAverageSales = obj.quantity / %d;
                            } else {
                                obj.dailyAverageSales = null;
                            }
                            return obj;
                        }",
                        $days
                    )
                )
            )
            ->out(array('inline' => true));

        return $query->getQuery()->execute();
    }

    /**
     * @return array
     */
    public function calculateGrossSales()
    {
        if ($this->isCollectionEmpty()) {
            return array();
        }

        $datePeriod = new DatePeriod("-8 day 00:00", "+1 day 23:59:59");

        $ops = array(
            array(
                '$match' => array(
                    'createdDate' => array(
                        '$gte' => $datePeriod->getStartDate()->getMongoDate(),
                        '$lt' => $datePeriod->getEndDate()->getMongoDate(),
                    ),
                    'reason.$ref' => SaleProduct::REASON_TYPE,
                ),
            ),
            array(
                '$sort' => array(
                    'createdDate' => 1,
                )
            ),
            array(
                '$project' => array(
                    'store' => 1,
                    'totalPrice' => 1,
                    'year' => array('$year' => '$createdDate'),
                    'month' => array('$month' => '$createdDate'),
                    'day' => array('$dayOfMonth' => '$createdDate'),
                    'hour' => array('$hour' => '$createdDate'),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array(
                        'store' => '$store',
                        'year' => '$year',
                        'month' => '$month',
                        'day' => '$day',
                        'hour' => '$hour',
                    ),
                    'hourSum' => array('$sum' => '$totalPrice'),
                ),
            ),
        );

        return $this->aggregate($ops);
    }

    /**
     * @param Store[] $stores
     * @param int $countProducts
     * @return array
     * @throws \Exception
     */
    public function calculateGrossSalesProduct($stores, $countProducts)
    {
        $results = array(
            'reports' => array(),
            'totalCount' => 0,
        );

        if ($this->isCollectionEmpty()) {
            return $results;
        }

        $requireDatePeriod = new DatePeriod("-8 day 00:00", "+1 day 23:59:59");

        $maxHoursStep = (int) (self::MAX_STORE_GROSS_SALES_REPORT_AGGREGATION / $countProducts);
        if ($maxHoursStep < 1) {
            $maxHoursStep = 1;
        }

        $countSteps = 1;

        $countAllSteps = ((24 * 10) / $maxHoursStep) * count($stores);

        $startCalc = microtime(true);
        $totalDurationAggregateTime = 0.0;
        $totalMergeTime = 0.0;

        foreach ($stores as $store) {
            $startDate = clone $requireDatePeriod->getStartDate();
            $endDate = clone $startDate;
            $endDate->modify("+{$maxHoursStep} hour");
            if ($endDate > $requireDatePeriod->getEndDate()) {
                $endDate = $requireDatePeriod->getEndDate();
            }

            while (1) {
                $startAggregateTime = microtime(true);
                $stepResult = $this->grossSalesProductAggregate($startDate, $endDate, $store);
                $durationAggregateTime = microtime(true) - $startAggregateTime;

                $startMergeTime = microtime(true);
                if (0 !== count($stepResult)) {
                    $results['reports'][] = $stepResult;
                    $results['totalCount'] += count($stepResult);
                }
                $durationMergeTime = microtime(true) - $startMergeTime;

                $totalDurationAggregateTime += $durationAggregateTime;
                $totalMergeTime += $durationMergeTime;

//                    printf(
//                        "Получено: %6d, Агрегация: %6f|%6f, Мердж: %6f|%6f, Шаг: %3d/%3d \n",
//                        count($stepResult['result']),
//                        $durationAggregateTime,
//                        $totalDurationAggregateTime,
//                        $durationMergeTime,
//                        $totalMergeTime,
//                        $countSteps++,
//                        $countAllSteps
//                    );

                if ($endDate >= $requireDatePeriod->getEndDate()) {
                    break;
                }

                $startDate = clone $endDate;
                $endDate->modify("+{$maxHoursStep} hour");
                if ($endDate > $requireDatePeriod->getEndDate()) {
                    $endDate = $requireDatePeriod->getEndDate();
                }
            }
        }

        $durationCalc = microtime(true) - $startCalc;

//        echo "Время на расчёт: $durationCalc, Всего записей: ". $results['totalCount'] ."\n";

        return $results;
    }

    /**
     * @param DateTimestamp $startDate
     * @param DateTimestamp $endDate
     * @param Store $store
     * @return array
     */
    protected function grossSalesProductAggregate(
        DateTimestamp $startDate,
        DateTimestamp $endDate,
        Store $store
    ) {
        $ops = array(
            array(
                '$match' => array(
                    'createdDate' => array(
                        '$gte' => $startDate->getMongoDate(),
                        '$lt' => $endDate->getMongoDate(),
                    ),
                    'reason.$ref' => SaleProduct::REASON_TYPE,
                    'store' => new MongoId($store->id),
                ),
            ),
            array(
                '$sort' => array(
                    'createdDate' => 1,
                )
            ),
            array(
                '$project' => array(
                    'storeProduct' => 1,
                    'totalPrice' => 1,
                    'year' => array('$year' => '$createdDate'),
                    'month' => array('$month' => '$createdDate'),
                    'day' => array('$dayOfMonth' => '$createdDate'),
                    'hour' => array('$hour' => '$createdDate'),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array(
                        'storeProduct' => '$storeProduct',
                        'year' => '$year',
                        'month' => '$month',
                        'day' => '$day',
                        'hour' => '$hour',
                    ),
                    'hourSum' => array('$sum' => '$totalPrice'),
                ),
            ),
        );


        return $this->aggregate($ops);
    }

    /**
     * @param string $reasonType
     * @param string $storeProductId
     * @param Quantity $startIndex
     * @param Quantity $endIndex
     * @return Cursor|TrialBalance[]
     */
    public function findByIndexRange($reasonType, $storeProductId, Quantity $startIndex, Quantity $endIndex)
    {
        $criteria = array(
            'reason.$ref' => $reasonType,
            'storeProduct' => $storeProductId,
            'endIndex.count' => array('$gt' => $startIndex->getCount()),
            'startIndex.count' => array('$lt' => $endIndex->getCount()),
        );
        $sort = array(
            'createdDate' => self::SORT_ASC,
            '_id' => self::SORT_ASC,
        );
        return $this->findBy($criteria, $sort);
    }
}
