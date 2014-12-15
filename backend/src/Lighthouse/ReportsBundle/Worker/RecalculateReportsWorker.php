<?php

namespace Lighthouse\ReportsBundle\Worker;

use Lighthouse\JobBundle\Document\Job\Job;
use Lighthouse\JobBundle\Worker\WorkerInterface;

class RecalculateReportsWorker implements WorkerInterface
{
    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job)
    {
        // TODO: Implement supports() method.
    }

    /**
     * @param Job $job
     * @return mixed result of work
     */
    public function work(Job $job)
    {
        // TODO: Implement work() method.
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'recalculate_reports';
    }
}
