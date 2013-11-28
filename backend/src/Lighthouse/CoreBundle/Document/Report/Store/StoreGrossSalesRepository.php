<?php

namespace Lighthouse\CoreBundle\Document\Report\Store;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;
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
}
