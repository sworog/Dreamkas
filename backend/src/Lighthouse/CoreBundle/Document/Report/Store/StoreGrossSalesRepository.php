<?php

namespace Lighthouse\CoreBundle\Document\Report\Store;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use MongoId;

class StoreGrossSalesRepository extends DocumentRepository
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
     * @param DateTime $dayHour
     * @param Store $store
     * @return StoreGrossSalesReport
     */
    public function createByDayHourAndStore(DateTime $dayHour, Store $store)
    {
        $report = new StoreGrossSalesReport();
        $report->id = $this->getIdByStoreIdAndDayHour($store->id, $dayHour);
        $report->dayHour = $dayHour;
        $report->store = $store;
        $report->runningSum = new Money(0);
        $report->hourSum = new Money(0);

        return $report;
    }

    /**
     * @param string $storeId
     * @param DateTime $dayHour
     * @param int $runningSum
     * @param int $hourSum
     */
    public function updateStoreDayHourGrossSales($storeId, DateTime $dayHour, $runningSum, $hourSum)
    {
        $reportId = $this->getIdByStoreIdAndDayHour($storeId, $dayHour);
        $store = $this->dm->getReference(Store::getClassName(), $storeId);

        $report = new StoreGrossSalesReport();
        $report->id = $reportId;
        $report->store = $store;
        $report->dayHour = $dayHour;
        $report->runningSum = new Money($runningSum);
        $report->hourSum = new Money($hourSum);

        $this->dm->persist($report);

        $this->dm->flush();
    }

    /**
     * @param string $storeId
     * @param string|DateTime $dayHour
     * @return null|StoreGrossSalesReport
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
     * @param Store $store
     * @param string $date
     * @return Cursor
     */
    public function findByStoreAndDate(Store $store, $date)
    {
        $dates = $this->getDatesForFullDayReport($date);
        $ids = $this->getIdsByStoreAndDateArray($store, $dates);

        $query = $this
            ->createQueryBuilder()
            ->field('_id')->in(array_values($ids));

        return $query->getQuery()->execute();
    }

    /**
     * @param DateTime[]|array $dates
     * @return StoreGrossSalesReport[]|Cursor
     */
    public function findByDates(array $dates)
    {
        $queryDates = $this->normalizeDates($dates);
        return $this->findBy(array('dayHour' => array('$in' => $queryDates)));
    }

    /**
     * @param DateTime[] $dates
     * @return DateTime[]
     */
    protected function normalizeDates(array $dates)
    {
        $queryDates = array_map(
            function (DateTime $date) {
                $queryDate = clone $date;
                $queryDate->setTimezone(new \DateTimeZone('UTC'));
                return $queryDate;
            },
            array_values($dates)
        );
        return $queryDates;
    }

    /**
     * @param string|DateTime $date
     * @return array
     */
    public function getDatesForFullDayReport($date)
    {
        $dates = array(
            'nowDayHour' => null,
            'yesterdayNowDayHour' => null,
            'yesterdayEndDay' => null,
            'weekAgoNowDayHour' => null,
            'weekAgoEndDay' => null,
        );

        $nowDayHour = new DateTimestamp($date);
        $nowDayHour->setTime($nowDayHour->format("G"), 0, 0);
        $nowDayHour->modify("-1 hour");
        $dates['nowDayHour'] = $nowDayHour;

        $yesterdayNowDayHour = clone $nowDayHour;
        $yesterdayNowDayHour->modify("-1 day");
        $yesterdayEndDay = clone $yesterdayNowDayHour;
        $yesterdayEndDay->setTime(23, 0);
        $dates['yesterdayNowDayHour'] = $yesterdayNowDayHour;
        $dates['yesterdayEndDay'] = $yesterdayEndDay;

        $weekAgoNowDayHour = clone $nowDayHour;
        $weekAgoNowDayHour->modify("-7 day");
        $weekAgoEndDay = clone $weekAgoNowDayHour;
        $weekAgoEndDay->setTime(23, 0);
        $dates['weekAgoNowDayHour'] = $weekAgoNowDayHour;
        $dates['weekAgoEndDay'] = $weekAgoEndDay;

        return $dates;
    }

    /**
     * @param Store $store
     * @param array $dates
     * @return array
     */
    public function getIdsByStoreAndDateArray(Store $store, $dates)
    {
        $ids = array();
        foreach ($dates as $key => $value) {
            $ids[$key] = $this->getIdByStoreIdAndDayHour($store->id, $value);
        }

        return $ids;
    }

    /**
     * Поиск записей с начала дня и до текущего часа в дате
     * @param Store $store
     * @param $date
     * @return StoreGrossSalesReportCollection
     */
    public function findByStoreAndDateLimitDayHour(Store $store, $date)
    {
        $date = new DateTimestamp($date);
        $date->modify("-1 hour");
        $startDate = clone $date;
        $startDate->setTime(0, 0, 0);

        $query = $this->createQueryBuilder()
            ->field('store')->references($store)
            ->field('dayHour')->gte($startDate->getMongoDate())
            ->field('dayHour')->lte($date->getMongoDate())
            ->getQuery();

        $result = $query->execute();
        return new StoreGrossSalesReportCollection($result);
    }
}
