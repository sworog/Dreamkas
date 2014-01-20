<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByCategories;

use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByClassifierNodeCollection;
use Lighthouse\ReportsBundle\Reports\GrossSales\TodayGrossSales;

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
