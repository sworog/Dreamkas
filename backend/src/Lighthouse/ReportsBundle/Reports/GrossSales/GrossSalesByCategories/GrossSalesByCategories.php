<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByCategories;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByClassifierNode;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

class GrossSalesByCategories extends GrossSalesByClassifierNode
{
    /**
     * @Serializer\MaxDepth(2)
     * @var category
     */
    protected $category;

    /**
     * @param Category|AbstractNode $category
     * @param DateTime[] $dates
     */
    public function __construct(Category $category, array $dates)
    {
        $this->category = $category;
        parent::__construct($dates);
    }

    /**
     * @return AbstractNode
     */
    public function getNode()
    {
        return $this->category;
    }
}
