<?php

namespace Lighthouse\ReportsBundle\Document\GrossSales\Classifier\SubCategory;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\GrossSalesNodeRepository;

class GrossSalesSubCategoryRepository extends GrossSalesNodeRepository
{
    /**
     * @return GrossSalesSubCategoryReport
     */
    public function createReport()
    {
        return new GrossSalesSubCategoryReport();
    }

    /**
     * @return string
     */
    protected function getNodeClass()
    {
        return SubCategory::getClassName();
    }

    /**
     * @return string
     */
    protected function getNodeField()
    {
        return 'subCategory';
    }
}
