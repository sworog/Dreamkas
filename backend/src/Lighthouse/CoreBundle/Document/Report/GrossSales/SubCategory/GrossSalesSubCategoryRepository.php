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
     * @param Money $runningSum
     * @param Money $hourSum
     * @return GrossSalesSubCategoryReport
     */
    public function createByDayHourAndSubCategory(
        DateTime $dayHour,
        SubCategory $subCategory,
        Store $store,
        Money $hourSum = null,
        Money $runningSum = null
    ) {
        $report = new GrossSalesSubCategoryReport();
        $report->id = $this->getIdBySubCategoryAndDayHour($subCategory, $dayHour);
        $report->dayHour = $dayHour;
        $report->subCategory = $subCategory;
        $report->store = $store;
        $report->runningSum = $runningSum ?: new Money(0);
        $report->hourSum = $hourSum ?: new Money(0);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $subCategoryId
     * @param string $storeId
     * @param Money $hourSum
     * @param Money $runningSum
     * @return GrossSalesSubCategoryReport
     */
    public function createByDayHourAndSubCategoryId(
        DateTime $dayHour,
        $subCategoryId,
        $storeId,
        Money $hourSum = null,
        Money $runningSum = null
    ) {
        $subCategory = $this->dm->getReference(SubCategory::getClassName(), $subCategoryId);
        $store = $this->dm->getReference(Store::getClassName(), $storeId);
        return $this->createByDayHourAndSubCategory($dayHour, $subCategory, $store, $hourSum, $runningSum);
    }

    /**
     * @param array $dates
     * @param array $subCategoryIds
     * @param string $storeId
     * @return Cursor|GrossSalesSubCategoryReport[]
     */
    public function findByDayHoursStoreProducts(array $dates, array $subCategoryIds, $storeId)
    {
        $dates = $this->normalizeDates($dates);
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
     * @param array $dates
     * @return array
     */
    protected function normalizeDates(array $dates)
    {
        return call_user_func_array('array_merge', $dates);
    }

    /**
     * @param array $ids
     * @return MongoId[]
     */
    protected function convertToMongoIds(array $ids)
    {
        return array_map(
            function($id) {
                return new MongoId((string) $id);
            },
            $ids
        );
    }
}
