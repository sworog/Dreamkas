<?php

namespace Lighthouse\ReportsBundle\Document\GrossSales\Classifier\Category;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\GrossSalesNodeRepository;

class GrossSalesCategoryRepository extends GrossSalesNodeRepository
{
    /**
     * @return GrossSalesCategoryReport
     */
    public function createReport()
    {
        return new GrossSalesCategoryReport();
    }

    /**
     * @return string
     */
    protected function getNodeClass()
    {
        return Category::getClassName();
    }

    /**
     * @return string
     */
    protected function getNodeField()
    {
        return 'category';
    }
}
