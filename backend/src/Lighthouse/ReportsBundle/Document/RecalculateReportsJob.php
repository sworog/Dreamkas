<?php

namespace Lighthouse\ReportsBundle\Document;

use Lighthouse\JobBundle\Document\Job\Job;

class RecalculateReportsJob extends Job
{
    /**
     * @return array
     */
    public function getTubeData()
    {
        return parent::getTubeData();
    }
}
