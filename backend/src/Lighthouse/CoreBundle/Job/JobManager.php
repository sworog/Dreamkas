<?php

namespace Lighthouse\CoreBundle\Job;

use Lighthouse\CoreBundle\Job\Worker\WorkerManager;
use Lighthouse\CoreBundle\Exception\Job\NotFoundJobException;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;
use Pheanstalk_Exception_ServerException as PheanstalkServerException;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * @DI\Service("lighthouse.core.job.manager")
 * @DI\Tag("monolog.logger", attributes={"channel"="job"})
 */
class JobManager
{
    /**
     * @var \Lighthouse\CoreBundle\Job\Worker\WorkerManager
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @DI\InjectParams({
     *      "pheanstalk" = @DI\Inject("leezy.pheanstalk"),
     *      "jobRepository" = @DI\Inject("lighthouse.core.job.repository"),
     *      "workerManager" = @DI\Inject("lighthouse.core.job.worker.manager"),
     *      "logger" = @DI\Inject("logger")
     * })
     * @param PheanstalkInterface $pheanstalk
     * @param JobRepository $jobRepository
     * @param WorkerManager $workerManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        PheanstalkInterface $pheanstalk,
        JobRepository $jobRepository,
        WorkerManager $workerManager,
        LoggerInterface $logger
    ) {
        $this->pheanstalk = $pheanstalk;
        $this->jobRepository = $jobRepository;
        $this->workerManager = $workerManager;
        $this->logger = $logger;
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

    /**
     * @return $this
     */
    public function startWatchingTubes()
    {
        foreach ($this->workerManager->getNames() as $tubeName) {
            $this->pheanstalk->watch($tubeName);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function stopWatchingTubes()
    {
        foreach ($this->workerManager->getNames() as $tubeName) {
            $this->pheanstalk->ignore($tubeName);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function purgeTubes()
    {
        $this->purgeReserved();
        $this->purgeReady();
        $this->purgeDelayed();
        $this->purgeBuried();

        return $this;
    }

    /**
     *
     */
    protected function purgeReserved()
    {
        while (true) {
            $job = $this->pheanstalk->reserve(0);
            if (false === $job) {
                break;
            }
            $this->pheanstalk->delete($job);
        }
    }

    /**
     *
     */
    protected function purgeReady()
    {
        try {
            while (true) {
                $job = $this->pheanstalk->peekReady();
                $this->pheanstalk->delete($job);
            }
        } catch (PheanstalkServerException $e) {
        }
    }

    /**
     *
     */
    protected function purgeDelayed()
    {
        try {
            while (true) {
                $job = $this->pheanstalk->peekDelayed();
                $this->pheanstalk->delete($job);
            }
        } catch (PheanstalkServerException $e) {
        }
    }

    /**
     *
     */
    protected function purgeBuried()
    {
        try {
            while (true) {
                $job = $this->pheanstalk->peekBuried();
                $this->pheanstalk->delete($job);
            }
        } catch (PheanstalkServerException $e) {
        }
    }

    /**
     * @param int|null $timeout
     * @return Job|null
     * @throws NotFoundJobException
     */
    public function reserveJob($timeout = null)
    {
        /* @var \Pheanstalk_Job $tubeJob */
        $tubeJob = $this->pheanstalk->reserve($timeout);
        if (!$tubeJob) {
            return null;
        }

        $jobId = $tubeJob->getData();

        /* @var Job $job */
        $job = $this->jobRepository->find($jobId);
        if (!$job) {
            $this->logger->critical(
                sprintf(
                    'Job #%s from tube was not found in repository by id #%s. Job will be deleted from tube.',
                    $tubeJob->getId(),
                    $jobId
                )
            );
            $this->pheanstalk->delete($tubeJob);
            throw new NotFoundJobException($jobId, $tubeJob->getId());
        }
        $job->setTubeJob($tubeJob);

        $job->setProcessingStatus();
        $this->jobRepository->save($job);

        return $job;
    }

    /**
     * @param Job $job
     */
    public function processJob(Job $job)
    {
        try {
            $worker = $this->workerManager->getByJob($job);
            $worker->work($job);

            $this->pheanstalk->delete($job->getTubeJob());

            $job->setSuccessStatus();
            $this->jobRepository->save($job);

        } catch (Exception $e) {
            $this->logger->emergency($e);

            $this->pheanstalk->delete($job->getTubeJob());

            $job->setFailStatus($e->getMessage());
            $this->jobRepository->save($job);
        }
    }
}
