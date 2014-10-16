<?php

namespace Lighthouse\ReportsBundle\Document\GrossSales\Product;

use Doctrine\MongoDB\ArrayIterator;
use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\Types\Type;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\MongoDB\Types\MoneyType;
use Lighthouse\CoreBundle\MongoDB\Types\QuantityType;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesCalculatable;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use MongoId;

class GrossSalesProductRepository extends DocumentRepository implements GrossSalesCalculatable
{
    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param NumericFactory $numericFactory
     */
    public function setNumericFactory(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }

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
     * @param Quantity $hourQuantity
     * @return GrossSalesProductReport
     */
    public function createByDayHourAndStoreProduct(
        DateTime $dayHour,
        StoreProduct $storeProduct,
        Money $runningSum = null,
        Money $hourSum = null,
        Quantity $hourQuantity = null
    ) {
        $storeProductId = $this->getDocumentIdentifierValue($storeProduct);
        $report = new GrossSalesProductReport();
        $report->id = $this->getIdByStoreProductIdAndDayHour($storeProductId, $dayHour);
        $report->dayHour = $dayHour;
        $report->product = $storeProduct;
        $report->runningSum = $runningSum ?: new Money(0);
        $report->hourSum = $hourSum ?: new Money(0);
        $report->hourQuantity = $hourQuantity ?: $this->numericFactory->createQuantity(0);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $storeProductId
     * @param Money $runningSum
     * @param Money $hourSum
     * @param Quantity $hourQuantity
     * @return GrossSalesProductReport
     */
    public function createByDayHourAndStoreProductId(
        DateTime $dayHour,
        $storeProductId,
        Money $runningSum = null,
        Money $hourSum = null,
        Quantity $hourQuantity = null
    ) {
        $storeProduct = $this->dm->getReference(StoreProduct::getClassName(), $storeProductId);
        return $this->createByDayHourAndStoreProduct($dayHour, $storeProduct, $runningSum, $hourSum, $hourQuantity);
    }

    /**
     * @param DateTimestamp[] $dates
     * @param array $storeProductIds
     * @return Cursor
     */
    public function findByDayHoursStoreProducts(array $dates, array $storeProductIds)
    {
        return $this->findBy(
            array(
                'dayHour' => array('$in' => $this->convertToType($dates, Type::DATE)),
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
            $productId = $this->getDocumentIdentifierValue($report->product);
            $arrayObj = array(
                '_id' => $report->id,
                'product' => $productId,
                'hourSum' => MoneyType::convertToMongo($report->hourSum),
                'hourQuantity' => QuantityType::convertToMongo($report->hourQuantity),
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
     * @param string|MongoId $storeId
     * @return ArrayIterator
     */
    public function calculateGrossSalesByIds(array $storeProductIds, $storeId)
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
