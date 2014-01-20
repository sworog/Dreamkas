<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByGroups;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByClassifierNode;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

class GrossSalesByGroups extends GrossSalesByClassifierNode
{
    /**
     * @Serializer\MaxDepth(2)
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

    /**
     * @return AbstractNode
     */
    public function getNode()
    {
        return $this->group;
    }
}
