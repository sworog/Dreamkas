<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\Query\Expr;
use Doctrine\ODM\MongoDB\Query\Query;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use MongoId;
use MongoCode;

class TrialBalanceRepository extends DocumentRepository
{
    /**
     * @param $storeProductId
     * @return Cursor
     */
    public function findByStoreProduct($storeProductId)
    {
        return $this->findBy(array('storeProduct' => $storeProductId));
    }

    /**
     * @param Reasonable $reason
     * @return TrialBalance
     */
    public function findOneByReason(Reasonable $reason)
    {
        $criteria = array(
            'reason.$id' => new \MongoId($reason->getReasonId()),
            'reason.$ref' => $reason->getReasonType(),
        );
        return $this->findOneBy($criteria);
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
            ->field('createdDate')->gt($datePeriod->getStartDate()->getMongoDate())
            ->field('createdDate')->lt($datePeriod->getEndDate()->getMongoDate())
            ->field('reason.$ref')->equals(InvoiceProduct::REASON_TYPE)
            ->map(
                new MongoCode(
                    "function() {
                        emit(
                            this.storeProduct,
                            {
                                totalPrice: this.totalPrice,
                                quantity: this.quantity
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
            ->finalize(
                new MongoCode(
                    "function(storeProductId, obj) {
                        if (obj.quantity > 0) {
                            obj.averagePrice = obj.totalPrice / obj.quantity;
                        } else {
                            obj.averagePrice = null;
                        }
                        return obj;
                    }"
                )
            )
            ->out(array('inline' => true));

        return $query->getQuery()->execute();
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
            ->map(
                new MongoCode(
                    "function() {
                        emit(
                            this.storeProduct,
                            {
                                quantity: this.quantity
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

    public function calculateGrossSales()
    {
        if ($this->isCollectionEmpty()) {
            return array();
        }

        $datePeriod = new DatePeriod("-10 day 00:00", "+1 day 23:59:59");
        $days = $datePeriod->diff()->days;

        $query = $this
            ->createQueryBuilder()
            ->field('createdDate')->gt($datePeriod->getStartDate()->getMongoDate())
            ->field('createdDate')->lt($datePeriod->getEndDate()->getMongoDate())
            ->field('reason.$ref')->equals(SaleProduct::REASON_TYPE)
            ->map(
                new MongoCode(
                    "function() {
                        var date = this.createdDate;
                        var createHour = date.getHours();
                        date.setHours(0, 0, 0, 0);
                        var key = {
                            store: this.store,
                            day: date
                        }
                        var hoursGrossSales = {
                            0: 0, 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0, 8: 0, 9: 0, 10: 0, 11: 0,
                            12: 0, 13: 0, 14: 0, 15: 0, 16: 0, 17: 0, 18: 0, 19: 0, 20: 0, 21: 0, 22: 0, 23: 0
                        }
                        for (var hour in hoursGrossSales) {
                            if (hour >= createHour) {
                                hoursGrossSales[hour] += this.totalPrice;
                            }
                        }
                        emit(
                            key,
                            hoursGrossSales
                        )
                    }"
                )
            )
            ->reduce(
                new MongoCode(
                    "function (key, hoursGrossSalesValues) {
                        var reducedHoursGrossSales = {
                            0: 0, 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0, 8: 0, 9: 0, 10: 0, 11: 0,
                            12: 0, 13: 0, 14: 0, 15: 0, 16: 0, 17: 0, 18: 0, 19: 0, 20: 0, 21: 0, 22: 0, 23: 0
                        }
                        for (var item in hoursGrossSalesValues) {
                            var hoursGrossSales = hoursGrossSalesValues[item];
                            for (var hour in hoursGrossSales) {
                                reducedHoursGrossSales[hour] += hoursGrossSales[hour];
                            }
                        }
                        return reducedHoursGrossSales;
                    }"
                )
            )
            ->out(array('inline' => true));

        return $query->getQuery()->execute();
    }
}
