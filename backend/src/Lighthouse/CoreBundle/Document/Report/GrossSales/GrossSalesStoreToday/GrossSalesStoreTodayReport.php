<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesStoreToday;

use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @property GrossSalesStoreTodayDay $today
 * @property GrossSalesStoreTodayDay $yesterday
 * @property GrossSalesStoreTodayDay $weekAgo
 */
class GrossSalesStoreTodayReport extends AbstractDocument
{
    /**
     * @var GrossSalesStoreTodayDay
     */
    protected $today;

    /**
     * @var GrossSalesStoreTodayDay
     */
    protected $yesterday;

    /**
     * @var GrossSalesStoreTodayDay
     */
    protected $weekAgo;

    /**
     * @param array $dates
     */
    public function __construct(array $dates)
    {
        foreach (array_keys($dates) as $position => $key) {
            $endDay = true;
            if (0 === $position) {
                $endDay = false;
            }
            $this->$key = new GrossSalesStoreTodayDay($endDay);
        }
    }
}
