<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByGroups;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesByClassifierNodeCollection;
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
