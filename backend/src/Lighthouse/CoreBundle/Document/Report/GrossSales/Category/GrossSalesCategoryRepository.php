<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Category;

use Doctrine\ODM\MongoDB\Proxy\Proxy;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;

class GrossSalesCategoryRepository extends DocumentRepository
{
    /**
     * @param Category $category
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdByCategoryAndDayHour(Category $category, $dayHour)
    {
        $categoryId = $this->getClassMetadata()->getIdentifierValue($category);
        return md5($categoryId . ":" . $dayHour->getTimestamp());
    }

    /**
     * @param DateTime $dayHour
     * @param Category|Proxy $category
     * @param Store $store
     * @param Money $hourSum
     * @return GrossSalesCategoryReport
     */
    public function createByDayHourAndCategory(
        DateTime $dayHour,
        Category $category,
        Store $store,
        Money $hourSum = null
    ) {
        $report = new GrossSalesCategoryReport();
        $report->id = $this->getIdByCategoryAndDayHour($category, $dayHour);
        $report->dayHour = $dayHour;
        $report->category = $category;
        $report->store = $store;
        $report->hourSum = $hourSum ?: new Money(0);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $categoryId
     * @param string $storeId
     * @param Money $hourSum
     * @return GrossSalesSubCategoryReport
     */
    public function createByDayHourAndCategoryId(
        DateTime $dayHour,
        $categoryId,
        $storeId,
        Money $hourSum = null
    ) {
        $category = $this->dm->getReference(Category::getClassName(), $categoryId);
        $store = $this->dm->getReference(Store::getClassName(), $storeId);
        return $this->createByDayHourAndCategory($dayHour, $category, $store, $hourSum);
    }

    /**
     * @param array $dates
     * @param array $categoryIds
     * @param string $storeId
     * @return Cursor|GrossSalesCategoryReport[]
     */
    public function findByDayHoursAndCategoryIds(array $dates, array $categoryIds, $storeId)
    {
        $categoryIds = $this->convertToMongoIds($categoryIds);
        return $this->findBy(
            array(
                'dayHour' => array('$in' => $dates),
                'category' => array('$in' => $categoryIds),
                'store' => $storeId,
            )
        );
    }
}
