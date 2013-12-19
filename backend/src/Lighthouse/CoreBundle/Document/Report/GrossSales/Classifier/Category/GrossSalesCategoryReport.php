<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\Category;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeReport;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property Category $category
 *
 * @MongoDB\Document(
 *      repositoryClass=
 *      "Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\Category\GrossSalesCategoryRepository"
 * )
 * @MongoDB\Index(keys={"dayHour"="asc", "category"="asc", "store"="asc"})
 */
class GrossSalesCategoryReport extends GrossSalesNodeReport
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\Category\Category",
     *     simple=true
     * )
     * @var Category
     */
    protected $category;

    /**
     * @return AbstractNode|Category
     */
    public function getNode()
    {
        return $this->category;
    }

    /**
     * @param AbstractNode $node
     */
    public function setNode(AbstractNode $node)
    {
        $this->category = $node;
    }
}
