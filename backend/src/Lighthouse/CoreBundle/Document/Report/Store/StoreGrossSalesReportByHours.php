<?php

namespace Lighthouse\CoreBundle\Document\Report\Store;

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
     */
    public function __construct($today, $yesterday, $weekAgo)
    {
        $this->today = $today;
        $this->yesterday = $yesterday;
        $this->weekAgo = $weekAgo;
    }
}
