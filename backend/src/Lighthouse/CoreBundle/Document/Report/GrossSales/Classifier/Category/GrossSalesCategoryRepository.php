<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\Category;

use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeReport;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeRepository;

class GrossSalesCategoryRepository extends GrossSalesNodeRepository
{
    /**
     * @return GrossSalesNodeReport|GrossSalesCategoryReport
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
