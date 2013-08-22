<?php

namespace Lighthouse\CoreBundle\Document\Job;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Exception\RuntimeException;

/**
 * @DI\Service("lighthouse.core.job.worker_manager");
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
     * @param string $name
     * @return WorkerInterface
     */
    public function get($name)
    {
        if (isset($this->workers[$name])) {
            return $this->workers[$name];
        }
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

        throw new RuntimeException('No worker found for job %s', get_class($job));
    }
}
