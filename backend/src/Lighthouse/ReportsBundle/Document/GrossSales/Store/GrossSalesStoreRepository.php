<?php

namespace Lighthouse\ReportsBundle\Document\GrossSales\Store;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;

class GrossSalesStoreRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdByStoreIdAndDayHour($storeId, DateTime $dayHour)
    {
        return md5($storeId . ":" . $dayHour->getTimestamp());
    }

    /**
     * @param Store $store
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdByStoreAndDayHour(Store $store, DateTime $dayHour)
    {
        $storeId = $this->getClassMetadata()->getIdentifierValue($store);
        return $this->getIdByStoreIdAndDayHour($storeId, $dayHour);
    }

    /**
     * @param DateTime $dayHour
     * @param Store $store
     * @param Money $hourSum
     * @return GrossSalesStoreReport
     */
    public function createByDayHourAndStore(
        DateTime $dayHour,
        Store $store,
        Money $hourSum = null
    ) {
        $report = new GrossSalesStoreReport();
        $report->id = $this->getIdByStoreAndDayHour($store, $dayHour);
        $report->dayHour = $dayHour;
        $report->store = $store;
        $report->hourSum = $hourSum ?: new Money(0);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $storeId
     * @param Money $hourSum
     * @return GrossSalesStoreReport
     */
    public function createByDayHourAndStoreId(
        DateTime $dayHour,
        $storeId,
        Money $hourSum = null
    ) {
        $store = $this->dm->getReference(Store::getClassName(), $storeId);
        return $this->createByDayHourAndStore($dayHour, $store, $hourSum);
    }

    /**
     * @param Store $store
     * @param array $dates
     * @return Cursor|GrossSalesStoreReport[]
     */
    public function findByStoreDayHours(Store $store, array $dates)
    {
        return $this->findByStoreIdDayHours($store->id, $dates);
    }

    /**
     * @param $storeId
     * @param array $dates
     * @return Cursor|GrossSalesStoreReport[]
     */
    public function findByStoreIdDayHours($storeId, array $dates)
    {
        return $this->findBy(
            array(
                'store' => $storeId,
                'dayHour' => array('$in' => $dates),
            )
        );
    }

    /**
     * @param string $storeId
     * @param string|DateTime $dayHour
     * @return null|GrossSalesStoreReport
     */
    public function findOneByStoreIdAndDayHour($storeId, $dayHour)
    {
        if (!$dayHour instanceof DateTime) {
            $dayHour = new DateTime($dayHour);
        }

        $reportId = $this->getIdByStoreIdAndDayHour($storeId, $dayHour);

        return $this->find($reportId);
    }

    /**
     * @param DateTime[]|array $dates
     * @return GrossSalesStoreReport[]|Cursor
     */
    public function findByDates(array $dates)
    {
        $queryDates = $this->normalizeDates($dates);
        return $this->findBy(array('dayHour' => array('$in' => $queryDates)), array('store' => 1));
    }

    /**
     * @param DateTime[] $dates
     * @return DateTime[]
     */
    protected function normalizeDates(array $dates)
    {
        return array_values($dates);
    }
}
