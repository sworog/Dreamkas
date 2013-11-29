<?php

namespace Lighthouse\CoreBundle\Document\Report\Store;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class StoreGrossSalesRepository extends DocumentRepository
{

    public function getIdByStoreIdAndDayHour($storeId, DateTime $dayHour)
    {
        return md5($storeId . ":" . $dayHour->getTimestamp());
    }

    public function updateStoreDayHourGrossSales($storeId, $dayHour, $grossSales)
    {
        $reportId = $this->getIdByStoreIdAndDayHour($storeId, $dayHour);

        $report = new StoreGrossSalesReport();
        $report->id = $reportId;
        $report->dayHour = $dayHour;
        $report->value = new Money($grossSales);

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

    public function findByStoreAndDate(Store $store, $date)
    {
        $dates = $this->getDatesForFullDayReport($date);
        $ids = $this->getIdsByStoreAndDateArray($store, $dates);

        $query = $this
            ->createQueryBuilder()
            ->field('_id')->in(array_values($ids));

        return $query->getQuery()->execute();
    }

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

    public function getIdsByStoreAndDateArray(Store $store, $dates)
    {
        $ids = array();
        foreach ($dates as $key => $value) {
            $ids[$key] = $this->getIdByStoreIdAndDayHour($store->id, $value);
        }

        return $ids;
    }
}
