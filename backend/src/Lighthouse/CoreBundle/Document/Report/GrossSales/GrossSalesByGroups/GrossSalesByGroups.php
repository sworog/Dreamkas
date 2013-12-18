<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByGroups;

use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Report\GrossSales\TodayGrossSales;
use DateTime;

class GrossSalesByGroups extends TodayGrossSales
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * @param Group $group
     * @param DateTime[] $dates
     */
    public function __construct(Group $group, array $dates)
    {
        $this->group = $group;
        parent::__construct($dates);
    }
}
