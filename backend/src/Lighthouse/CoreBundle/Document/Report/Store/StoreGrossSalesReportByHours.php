<?php

namespace Lighthouse\CoreBundle\Document\Report\Store;

use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use DateTime;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class StoreGrossSalesReportByHours
{
    /**
     * @var StoreGrossSalesReportCollection
     */
    protected $today;

    /**
     * @var StoreGrossSalesReportCollection
     */
    protected $yesterday;

    /**
     * @var StoreGrossSalesReportCollection
     */
    protected $weekAgo;

    /**
     * @param StoreGrossSalesReportCollection $today
     * @param StoreGrossSalesReportCollection $yesterday
     * @param StoreGrossSalesReportCollection $weekAgo
     * @param string $time   strtotime format
     */
    public function __construct($today, $yesterday, $weekAgo, $time)
    {
        $this->today = $today;
        $this->yesterday = $yesterday;
        $this->weekAgo = $weekAgo;

        $time = new DateTimestamp($time);
        $this->fillEmptyDays($time);
    }

    /**
     * @param DateTime $time
     */
    public function fillEmptyDays(DateTime $time)
    {
        if (0 == $this->today->count()) {
            for ($hour = 0; $hour < $time->format("G"); $hour++) {
                $emptyReport = new StoreGrossSalesReport();
                $emptyReport->hourSum = new Money(0);
                $emptyReport->runningSum = new Money(0);
                $emptyReport->dayHour = clone $time;
                $emptyReport->dayHour->setTime($hour, 0, 0);
                $this->today[$hour] = $emptyReport;
            }
        }

        if (0 == $this->yesterday->count()) {
            for ($hour = 0; $hour < $time->format("G"); $hour++) {
                $emptyReport = new StoreGrossSalesReport();
                $emptyReport->hourSum = new Money(0);
                $emptyReport->runningSum = new Money(0);
                $emptyReport->dayHour = clone $time;
                $emptyReport->dayHour->setTime($hour, 0, 0);
                $emptyReport->dayHour->modify("-1 day");
                $this->yesterday[$hour] = $emptyReport;
            }
        }

        if (0 == $this->weekAgo->count()) {
            for ($hour = 0; $hour < $time->format("G"); $hour++) {
                $emptyReport = new StoreGrossSalesReport();
                $emptyReport->hourSum = new Money(0);
                $emptyReport->runningSum = new Money(0);
                $emptyReport->dayHour = clone $time;
                $emptyReport->dayHour->setTime($hour, 0, 0);
                $emptyReport->dayHour->modify("-1 week");
                $this->weekAgo[$hour] = $emptyReport;
            }
        }
    }
}
