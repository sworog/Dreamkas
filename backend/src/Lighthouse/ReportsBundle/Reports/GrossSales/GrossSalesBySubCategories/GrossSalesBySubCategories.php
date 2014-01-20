<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesBySubCategories;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByClassifierNode;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

class GrossSalesBySubCategories extends GrossSalesByClassifierNode
{
    /**
     * @Serializer\MaxDepth(2)
     * @var SubCategory
     */
    protected $subCategory;

    /**
     * @param SubCategory $subCategory
     * @param DateTime[] $dates
     */
    public function __construct(SubCategory $subCategory, array $dates)
    {
        $this->subCategory = $subCategory;
        parent::__construct($dates);
    }

    /**
     * @return AbstractNode
     */
    public function getNode()
    {
        return $this->subCategory;
    }
}
