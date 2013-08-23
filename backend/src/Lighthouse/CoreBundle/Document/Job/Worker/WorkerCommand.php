<?php

namespace Lighthouse\CoreBundle\Document\Job\Worker;

use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Document\Job\JobRepository;
use Lighthouse\CoreBundle\Document\Job\Worker\WorkerManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;
use Exception;

/**
 * @DI\Service("lighthouse.core.job.worker.command")
 * @DI\Tag("console.command")
 * @DI\Tag("monolog.logger", attributes={"channel"="worker"})
 */
class WorkerCommand extends Command
{
    /**
     * @var PheanstalkProxy
     */
    protected $pheanstalk;

    /**
     * @var WorkerManager
     */
    protected $workerManager;

    /**
     * @var JobRepository
     */
    protected $jobRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @DI\InjectParams({
     *      "pheanstalk" = @DI\Inject("leezy.pheanstalk"),
     *      "workerManager" = @DI\Inject("lighthouse.core.job.worker.manager"),
     *      "jobRepository" = @DI\Inject("lighthouse.core.job.repository"),
     *      "logger" = @DI\Inject("logger")
     * })
     * @param PheanstalkProxy $pheanstalk
     * @param WorkerManager $workerManager
     * @param JobRepository $jobRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        PheanstalkProxy $pheanstalk,
        WorkerManager $workerManager,
        JobRepository $jobRepository,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->pheanstalk = $pheanstalk;
        $this->workerManager = $workerManager;
        $this->jobRepository = $jobRepository;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('lighthouse:worker');
        $this->addOption('max-run-duration', null, InputOption::VALUE_OPTIONAL, 'Max run time in seconds', 600);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $maxRunDuration = $input->getOption('max-run-duration');
        $runtimeDeadline = time() + $maxRunDuration;

        $this->startWatchingTubes();

        while ($runtimeDeadline > time()) {
            $this->processJob($output);
        }

        return 0;
    }

    protected function startWatchingTubes()
    {
        foreach ($this->workerManager->getNames() as $tubeName) {
            $this->pheanstalk->watch($tubeName);
        }
    }

    protected function processJob(OutputInterface $output)
    {
        $output->writeln('<info>Waiting for the job..</info>');

        $tubeJob = $this->pheanstalk->reserve(10);
        if (!$tubeJob) {
            return;
        }
        $output->writeln(sprintf('<info>Reserved job #%s</info>', $tubeJob->getId()));

        $jobId = $tubeJob->getData();

        /* @var Job $job */
        $job = $this->jobRepository->find($jobId);
        $job->setProcessingStatus();
        $this->jobRepository->save($job);

        try {
            $worker = $this->workerManager->getByJob($job);
            $worker->work($job);

            $this->pheanstalk->delete($tubeJob);

            $job->setSuccessStatus();
            $this->jobRepository->save($job);

        } catch (Exception $e) {
            $this->logger->emergency($e);

            $this->pheanstalk->delete($tubeJob);

            $job->setFailStatus($e->getMessage());
            $this->jobRepository->save($job);
        }
    }
}
