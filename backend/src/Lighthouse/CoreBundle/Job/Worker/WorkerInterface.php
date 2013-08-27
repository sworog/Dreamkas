<?php

namespace Lighthouse\CoreBundle\Job\Worker;

use Lighthouse\CoreBundle\Job\Job;

interface WorkerInterface
{
    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job);

    /**
     * @param \Lighthouse\CoreBundle\Job\Job $job
     * @return mixed result of work
     */
    public function work(Job $job);

    /**
     * @return mixed
     */
    public function getName();
}
