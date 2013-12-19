<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\Group;

use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeReport;
use Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\GrossSalesNodeRepository;

class GrossSalesGroupRepository extends GrossSalesNodeRepository
{
    /**
     * @return GrossSalesNodeReport|GrossSalesGroupReport
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
