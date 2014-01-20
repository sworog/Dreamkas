<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByGroups;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByClassifierNodeCollection;
use DateTime;

class GrossSalesByGroupsCollection extends GrossSalesByClassifierNodeCollection
{
    /**
     * @param Group|AbstractNode $group
     * @param DateTime[] $dates
     * @return GrossSalesByGroups
     */
    public function createReport(AbstractNode $group, array $dates)
    {
        return new GrossSalesByGroups($group, $dates);
    }
}
