<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Product;

use Doctrine\MongoDB\ArrayIterator;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSalesFilter;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSalesRepository;
use DateTime;

class GrossMarginSalesProductRepository extends GrossMarginSalesRepository
{
    /**
     * @param GrossMarginSalesFilter $filter
     * @param SubCategory $catalogGroup
     * @return GrossMarginSalesProduct[]|Cursor
     */
    public function findByFilterCatalogGroup(GrossMarginSalesFilter $filter, SubCategory $catalogGroup)
    {
        $criteria = array(
            'subCategory' => $catalogGroup->id,
            'day' => array(
                '$gte' => $filter->dateFrom,
                '$lt' => $filter->dateTo,
            ),
        );

        if ($filter->store) {
            $criteria['store'] = $filter->store->id;
        }

        return $this->findBy($criteria);
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param DateTime $day
     * @return GrossMarginSalesProduct
     */
    public function findOneByStoreIdProductIdAndDay($storeId, $productId, DateTime $day)
    {
        return $this->findOneBy(
            array(
                'store' => $storeId,
                'product' => $productId,
                'day' => $day,
            )
        );
    }

    /**
     * @param array $result
     * @return GrossMarginSalesProduct
     */
    protected function createReport(array $result)
    {
        $report = new GrossMarginSalesProduct();
        $report->product = $this->dm->getReference(Product::getClassName(), $result['_id']['product']);
        $report->subCategory = $this->dm->getReference(SubCategory::getClassName(), $result['_id']['subCategory']);
        $report->store = $this->dm->getReference(Store::getClassName(), $result['_id']['store']);

        return $report;
    }

    /**
     * @param DateTimestamp $dateFrom
     * @param DateTimestamp $dateTo
     * @return ArrayIterator
     */
    protected function aggregateByDays(DateTimestamp $dateFrom, DateTimestamp $dateTo)
    {
        $ops = array(
            array(
                '$match' => array(
                    'createdDate.date' => array(
                        '$gte' => $dateFrom->getMongoDate(),
                        '$lt' => $dateTo->getMongoDate(),
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
                    'totalPrice' => true,
                    'costOfGoods' => true,
                    'quantity' => true,
                    'store' => true,
                    'subCategory' => true,
                    'product' => true,
                    'year' => '$createdDate.year',
                    'month' => '$createdDate.month',
                    'day' => '$createdDate.day'
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array(
                        'store' => '$store',
                        'product' => '$product',
                        'subCategory' => '$subCategory',
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
                    ),
                    'grossMargin' => array(
                        '$sum' => array(
                            '$subtract' => array('$totalPrice', '$costOfGoods')
                        ),
                    )
                ),
            ),
        );

        return $this->trialBalanceRepository->aggregate($ops);
    }
}
