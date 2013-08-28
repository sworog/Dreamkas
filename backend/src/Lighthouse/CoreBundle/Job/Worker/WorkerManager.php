<?php

namespace Lighthouse\CoreBundle\Job\Worker;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Job\Job;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;

/**
 * @DI\Service("lighthouse.core.job.worker.manager");
 */
class WorkerManager
{
    /**
     * @var WorkerInterface[]
     */
    protected $workers = array();

    /**
     * @param WorkerInterface $worker
     */
    public function add(WorkerInterface $worker)
    {
        $this->workers[$worker->getName()] = $worker;
    }

    /**
     * @return array|WorkerInterface[]
     */
    public function getAll()
    {
        return $this->workers;
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return array_keys($this->workers);
    }

    /**
     * @param Job $job
     * @return WorkerInterface
     * @throws \Lighthouse\CoreBundle\Exception\RuntimeException
     */
    public function getByJob(Job $job)
    {
        foreach ($this->workers as $worker) {
            if ($worker->supports($job)) {
                return $worker;
            }
        }

        throw new RuntimeException(sprintf('No worker found for job %s', get_class($job)));
    }
}
