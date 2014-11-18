<?php

namespace Lighthouse\JobBundle\Worker;

use Lighthouse\JobBundle\Document\Job\Job;

interface WorkerInterface
{
    /**
     * @param \Lighthouse\JobBundle\Document\\Lighthouse\JobBundle\Document\Job\Job $job
     * @return boolean
     */
    public function supports(Job $job);

    /**
     * @param \Lighthouse\JobBundle\Document\\Lighthouse\JobBundle\Document\Job\Job $job
     * @return mixed result of work
     */
    public function work(Job $job);

    /**
     * @return mixed
     */
    public function getName();
}
