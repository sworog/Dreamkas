<?php

namespace Lighthouse\ReportsBundle\Document\GrossSales\Classifier\Group;

use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\ReportsBundle\Document\GrossSales\Classifier\GrossSalesNodeRepository;

class GrossSalesGroupRepository extends GrossSalesNodeRepository
{
    /**
     * @return GrossSalesGroupReport
     */
    public function createReport()
    {
        return new GrossSalesGroupReport();
    }

    /**
     * @return string
     */
    protected function getNodeClass()
    {
        return Group::getClassName();
    }

    /**
     * @return string
     */
    protected function getNodeField()
    {
        return 'group';
    }
}
