<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByCategories;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Report\GrossSales\TodayGrossSales;
use DateTime;

class GrossSalesByCategories extends TodayGrossSales
{
    /**
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
}
