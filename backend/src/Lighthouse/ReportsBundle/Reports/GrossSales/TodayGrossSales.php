<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use DateTime;
use Lighthouse\ReportsBundle\Reports\GrossSales\DayHourGrossSales;

abstract class TodayGrossSales extends AbstractDocument
{
    /**
     * @var DayHourGrossSales
     */
    protected $today;

    /**
     * @var DayHourGrossSales
     */
    protected $yesterday;

    /**
     * @var DayHourGrossSales
     */
    protected $weekAgo;

    /**
     * @param DateTime[] $dates
     */
    public function __construct(array $dates)
    {
        foreach ($dates as $key => $dayHour) {
            $this->$key = new DayHourGrossSales($dayHour);
        }
    }
}
