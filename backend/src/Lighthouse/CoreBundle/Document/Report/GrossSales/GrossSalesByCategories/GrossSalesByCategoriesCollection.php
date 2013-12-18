<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByCategories;

use Lighthouse\CoreBundle\Document\AbstractCollection;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;

class GrossSalesByCategoriesCollection extends AbstractCollection
{
    /**
     * @param Category $category
     * @param DateTime[] $dates
     * @return GrossSalesByCategories
     */
    public function createByCategory(Category $category, array $dates)
    {
        $report = new GrossSalesByCategories($category, $dates);
        $this->set($category->id, $report);
        return $report;
    }
}
