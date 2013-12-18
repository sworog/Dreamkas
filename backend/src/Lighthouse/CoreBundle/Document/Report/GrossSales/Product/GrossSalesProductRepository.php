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
            $report = $this->createByDayHourAndStoreProductId($dayHour, $storeProductId);
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
     * @param array $dates
     * @param array $storeProductIds
     * @return Cursor
     */
    public function findByDayHoursStoreProducts(array $dates, array $storeProductIds)
    {
        return $this->findBy(
            array(
                'dayHour' => array('$in' => $dates),
                'product' => array('$in' => $storeProductIds)
            )
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

    /**
     * @param array $storeProductIds
     * @return array
     */
    public function calculateGrossSalesByIds(array $storeProductIds)
    {
        $ops = array(
            array(
                '$match' => array(
                    'product' => array(
                        '$in' => $storeProductIds
                    )
                ),
            ),
            array(
                '$sort' => array(
                    'dayHour' => 1,
                )
            ),
            array(
                '$project' => array(
                    '_id' => 0,
                    'dayHour' => 1,
                    'hourSum' => 1,
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$dayHour',
                    'hourSum' => array('$sum' => '$hourSum'),
                ),
            ),
        );
        return $this->aggregate($ops);
    }
}
