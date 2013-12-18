<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\SubCategory;

use Doctrine\ODM\MongoDB\Proxy\Proxy;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use MongoId;

class GrossSalesSubCategoryRepository extends DocumentRepository
{
    /**
     * @param SubCategory $subCategory
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdBySubCategoryAndDayHour(SubCategory $subCategory, $dayHour)
    {
        $subCategoryId = $this->getClassMetadata()->getIdentifierValue($subCategory);
        return md5($subCategoryId . ":" . $dayHour->getTimestamp());
    }

    /**
     * @param DateTime $dayHour
     * @param SubCategory|Proxy $subCategory
     * @param Store $store
     * @param Money $hourSum
     * @return GrossSalesSubCategoryReport
     */
    public function createByDayHourAndSubCategory(
        DateTime $dayHour,
        SubCategory $subCategory,
        Store $store,
        Money $hourSum = null
    ) {
        $report = new GrossSalesSubCategoryReport();
        $report->id = $this->getIdBySubCategoryAndDayHour($subCategory, $dayHour);
        $report->dayHour = $dayHour;
        $report->subCategory = $subCategory;
        $report->store = $store;
        $report->hourSum = $hourSum ?: new Money(0);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $subCategoryId
     * @param string $storeId
     * @param Money $hourSum
     * @return GrossSalesSubCategoryReport
     */
    public function createByDayHourAndSubCategoryId(
        DateTime $dayHour,
        $subCategoryId,
        $storeId,
        Money $hourSum = null
    ) {
        $subCategory = $this->dm->getReference(SubCategory::getClassName(), $subCategoryId);
        $store = $this->dm->getReference(Store::getClassName(), $storeId);
        return $this->createByDayHourAndSubCategory($dayHour, $subCategory, $store, $hourSum);
    }

    /**
     * @param array $dates
     * @param array $subCategoryIds
     * @param string $storeId
     * @return Cursor|GrossSalesSubCategoryReport[]
     */
    public function findByDayHoursAndSubCategoryIds(array $dates, array $subCategoryIds, $storeId)
    {
        $subCategoryIds = $this->convertToMongoIds($subCategoryIds);
        return $this->findBy(
            array(
                'dayHour' => array('$in' => $dates),
                'subCategory' => array('$in'=> $subCategoryIds),
                'store' => $storeId,
            )
        );
    }


    /**
     * @param array $ids
     * @return array
     */
    public function calculateGrossSalesByIds(array $ids)
    {
        $ops = array(
            array(
                '$match' => array(
                    'subCategory' => array(
                        '$in' => $ids
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
