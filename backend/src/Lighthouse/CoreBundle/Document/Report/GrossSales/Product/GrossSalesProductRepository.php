<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Product;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductCollection;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class GrossSalesProductRepository extends DocumentRepository
{
    /**
     * @param string $storeProductId
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdByStoreProductIdAndDayHour($storeProductId, $dayHour)
    {
        return md5($storeProductId . ":" . $dayHour->getTimestamp());
    }

    /**
     * @param string $storeProductId
     * @param DateTime|string $dayHour
     * @return GrossSalesProductReport|object
     */
    public function findByStoreProductAndDayHour($storeProductId, $dayHour)
    {
        if (!$dayHour instanceof DateTime) {
            $dayHour = new DateTime($dayHour);
        }

        $reportId = $this->getIdByStoreProductIdAndDayHour($storeProductId, $dayHour);

        $report = $this->find($reportId);

        if (null === $report) {
            $report = new GrossSalesProductReport();
            $report->id = $reportId;
            $report->dayHour = $dayHour;
            $report->hourSum = new Money(0);
            $report->runningSum = new Money(0);
        }

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param StoreProduct $storeProduct
     * @param Money $runningSum
     * @param Money $hourSum
     * @return GrossSalesProductReport
     */
    public function createByDayHourAndStoreProduct(
        DateTime $dayHour,
        StoreProduct $storeProduct,
        Money $runningSum = null,
        Money $hourSum = null
    ) {
        $report = new GrossSalesProductReport();
        $report->id = $this->getIdByStoreProductIdAndDayHour($storeProduct->id, $dayHour);
        $report->dayHour = $dayHour;
        $report->product = $storeProduct;
        $report->runningSum = $runningSum ?: new Money(0);
        $report->hourSum = $hourSum ?: new Money(0);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $storeProductId
     * @param Money $runningSum
     * @param Money $hourSum
     * @return GrossSalesProductReport
     */
    public function createByDayHourAndStoreProductId(
        DateTime $dayHour,
        $storeProductId,
        Money $runningSum = null,
        Money $hourSum = null
    ) {
        $storeProduct = $this->dm->getReference(StoreProduct::getClassName(), $storeProductId);
        return $this->createByDayHourAndStoreProduct($dayHour, $storeProduct, $runningSum, $hourSum);
    }

    /**
     * @param array $dayHours
     * @param StoreProductCollection $storeProducts
     * @return Cursor
     */
    public function findByDayHoursStoreProducts(array $dayHours, StoreProductCollection $storeProducts)
    {
        $datesForQuery = $this->normalizeDayHours($dayHours);
        $storeProductsForQuery = $this->normalizeStoreProducts($storeProducts);

        return $this->findBy(
            array(
                'dayHour' => array('$in' => $datesForQuery),
                'product' => array('$in'=> $storeProductsForQuery)
            )
        );
    }

    /**
     * @param array $dayHours
     * @return array
     */
    public function normalizeDayHours(array $dayHours)
    {
        return array_merge($dayHours['today'], $dayHours['yesterday'], $dayHours['weekAgo']);
    }

    /**
     * @param StoreProductCollection $collection
     * @return array
     */
    public function normalizeStoreProducts(StoreProductCollection $collection)
    {
        $array = $collection->toArray();
        return array_map(
            function ($value) {
                return $value->id;
            },
            $array
        );
    }

    /**
     * @param GrossSalesProductReport[] $reports
     */
    public function rawUpsertReports(array $reports)
    {
        $collection = $this->getDocumentManager()->getDocumentCollection(GrossSalesProductReport::getClassName());
        foreach ($reports as $report) {
            $date = new DateTimestamp($report->dayHour);
            $arrayObj = array(
                '_id' => $report->id,
                'product' => $report->product->id,
                'hourSum' => $report->hourSum->getCount(),
                'dayHour' => $date->getMongoDate(),
            );
            $collection->upsert(
                array('_id' => $arrayObj['_id']),
                $arrayObj,
                array('w' => 0, 'j' => 0)
            );
        }
    }
}
