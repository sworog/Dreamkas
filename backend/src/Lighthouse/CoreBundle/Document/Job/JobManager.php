<?php

namespace Lighthouse\CoreBundle\Document\Job;

use Lighthouse\CoreBundle\Document\Job\Worker\WorkerManager;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.job.manager");
 */
class JobManager
{
    /**
     * @var WorkerManager
     */
    protected $workerManager;

    /**
     * @var JobRepository
     */
    protected $jobRepository;

    /**
     * @var PheanstalkInterface
     */
    protected $pheanstalk;

    /**
     * @DI\InjectParams({
     *      "pheanstalk" = @DI\Inject("leezy.pheanstalk"),
     *      "jobRepository" = @DI\Inject("lighthouse.core.job.repository"),
     *      "workerManager" = @DI\Inject("lighthouse.core.job.worker.manager")
     * })
     * @param PheanstalkInterface $pheanstalk
     * @param JobRepository $jobRepository
     * @param WorkerManager $workerManager
     */
    public function __construct(
        PheanstalkInterface $pheanstalk,
        JobRepository $jobRepository,
        WorkerManager $workerManager
    ) {
        $this->pheanstalk = $pheanstalk;
        $this->jobRepository = $jobRepository;
        $this->workerManager = $workerManager;
    }

    /**
     * @param Job $job
     */
    public function addJob(Job $job)
    {
        // save job if it was not saved before
        $this->jobRepository->save($job);

        $jobId = $this->putJobInTube($job);

        $job->setPendingStatus($jobId);
        $this->jobRepository->save($job);
    }

    /**
     * @param Job $job
     * @return int tube job id
     */
    protected function putJobInTube(Job $job)
    {
        $worker = $this->workerManager->getByJob($job);
        $tubeName = $worker->getName();
        return $this->pheanstalk->putInTube($tubeName, $job->id);
    }
}
