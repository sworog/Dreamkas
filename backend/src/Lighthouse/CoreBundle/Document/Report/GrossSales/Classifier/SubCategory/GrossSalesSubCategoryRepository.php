<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\SubCategory;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeReport;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeRepository;

class GrossSalesSubCategoryRepository extends GrossSalesNodeRepository
{
    /**
     * @return GrossSalesNodeReport|GrossSalesSubCategoryReport
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
