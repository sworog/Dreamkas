<?php

namespace Lighthouse\CoreBundle\Document\Job\Worker;

use Lighthouse\CoreBundle\Document\Job\Job;

interface WorkerInterface
{
    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job);

    /**
     * @param Job $job
     * @return mixed result of work
     */
    public function work(Job $job);

    /**
     * @return mixed
     */
    public function getName();
}
