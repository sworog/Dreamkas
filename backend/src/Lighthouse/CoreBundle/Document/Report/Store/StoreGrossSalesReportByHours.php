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
     * @param string $time   str to time format
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
        $todayEmpty = !$this->today->count();
        $yesterdayEmpty = !$this->yesterday->count();
        $weekAgoEmpty = !$this->weekAgo->count();
        if ($todayEmpty || $yesterdayEmpty || $weekAgoEmpty) {
            for ($hour = 0; $hour < $time->format("G"); $hour++) {
                $emptyReport = new StoreGrossSalesReport();
                $emptyReport->hourSum = new Money(0);
                $emptyReport->runningSum = new Money(0);
                $emptyReport->dayHour = clone $time;
                $emptyReport->dayHour->setTime($hour, 0, 0);

                if ($todayEmpty) {
                    $this->today[$hour] = clone $emptyReport;
                }
                if ($yesterdayEmpty) {
                    $yesterdayEmptyReport = clone $emptyReport;
                    $yesterdayEmptyReport->dayHour = clone $emptyReport->dayHour;
                    $yesterdayEmptyReport->dayHour->modify("-1 day");
                    $this->yesterday[$hour] = $yesterdayEmptyReport;
                }
                if ($weekAgoEmpty) {
                    $weekAgoEmptyReport = clone $emptyReport;
                    $weekAgoEmptyReport->dayHour = clone $emptyReport->dayHour;
                    $weekAgoEmptyReport->dayHour->modify("-1 week");
                    $this->weekAgo[$hour] = $weekAgoEmptyReport;
                }
            }
        }
    }
}
