<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\SubCategory;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeReport;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property SubCategory    $subCategory
 *
 * @MongoDB\Document(
 *      repositoryClass=
 *      "Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\SubCategory\GrossSalesSubCategoryRepository"
 * )
 * @MongoDB\Index(keys={"dayHour"="asc", "subCategory"="asc", "store"="asc"})
 */
class GrossSalesSubCategoryReport extends GrossSalesNodeReport
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory",
     *     simple=true
     * )
     * @var SubCategory
     */
    protected $subCategory;

    /**
     * @return AbstractNode|SubCategory
     */
    public function getNode()
    {
        return $this->subCategory;
    }

    /**
     * @param AbstractNode $node
     */
    public function setNode(AbstractNode $node)
    {
        $this->subCategory = $node;
    }
}
