<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\Group;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesClassifierNodeReport;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property Group $group
 *
 * @MongoDB\Document(
 *      repositoryClass=
 *      "Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\Group\GrossSalesGroupRepository"
 * )
 */
class GrossSalesGroupReport extends GrossSalesClassifierNodeReport
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\Group\Group",
     *     simple=true
     * )
     * @var Group
     */
    protected $group;

    /**
     * @return AbstractNode|Group
     */
    public function getNode()
    {
        return $this->group;
    }

    /**
     * @param AbstractNode $node
     */
    public function setNode(AbstractNode $node)
    {
        $this->group = $node;
    }
}
