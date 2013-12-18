<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesBySubCategories;

use Lighthouse\CoreBundle\Document\AbstractCollection;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;

class GrossSalesBySubCategoriesCollection extends AbstractCollection
{
    /**
     * @param SubCategory $subCategory
     * @param array $dates
     * @return GrossSalesBySubCategories
     */
    public function createBySubCategory(SubCategory $subCategory, array $dates)
    {
        $report = new GrossSalesBySubCategories($subCategory, $dates);
        $this->set($subCategory->id, $report);
        return $report;
    }
}
