<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use DateTime;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Store\GrossSalesStoreReport;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;

class TodayHoursGrossSales extends AbstractDocument
{
    /**
     * @var DayHoursGrossSalesCollection
     */
    protected $today;

    /**
     * @var DayHoursGrossSalesCollection
     */
    protected $yesterday;

    /**
     * @var DayHoursGrossSalesCollection
     */
    protected $weekAgo;

    /**
     * @param array $dates
     */
    public function __construct(array $dates)
    {
        foreach (array_keys($dates) as $key) {
            $this->$key = new DayHoursGrossSalesCollection();
        }
    }

    /**
     * @param array $dates
     * @return $this
     */
    public function normalize(array $dates)
    {
        foreach (array_keys($dates) as $key) {
            $this->$key->keySort();
        }

        return $this;
    }
}
