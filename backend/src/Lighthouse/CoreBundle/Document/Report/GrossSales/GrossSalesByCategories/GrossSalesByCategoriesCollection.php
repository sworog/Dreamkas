<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByCategories;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByClassifierNodeCollection;
use Lighthouse\CoreBundle\Document\Report\GrossSales\TodayGrossSales;

class GrossSalesByCategoriesCollection extends GrossSalesByClassifierNodeCollection
{
    /**
     * @param AbstractNode|Category $node
     * @param array $dates
     * @return TodayGrossSales
     */
    public function createReport(AbstractNode $node, array $dates)
    {
        return new GrossSalesByCategories($node, $dates);
    }
}
