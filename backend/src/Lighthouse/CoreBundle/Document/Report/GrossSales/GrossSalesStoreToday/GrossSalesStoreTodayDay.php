<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesStoreToday;

use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @property GrossSalesStoreTodayDayMoment $now
 * @property GrossSalesStoreTodayDayMoment $dayEnd
 */
class GrossSalesStoreTodayDay extends AbstractDocument
{
    /**
     * @var GrossSalesStoreTodayDayMoment
     */
    protected $now;

    /**
     * @var null|GrossSalesStoreTodayDayMoment
     */
    protected $dayEnd = null;

    /**
     * @param boolean $dayEnd
     */
    public function __construct($dayEnd)
    {
        $this->now = new GrossSalesStoreTodayDayMoment();
        if ($dayEnd) {
            $this->dayEnd = new GrossSalesStoreTodayDayMoment();
        }
    }
}
