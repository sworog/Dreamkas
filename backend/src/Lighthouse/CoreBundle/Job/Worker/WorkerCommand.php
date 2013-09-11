<?php

namespace Lighthouse\CoreBundle\Job\Worker;

use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Exception\Job\NotFoundJobException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.job.worker.command")
 * @DI\Tag("console.command")
 * @DI\Tag("monolog.logger", attributes={"channel"="worker"})
 */
class WorkerCommand extends Command
{
    /**
     * @var JobManager
     */
    protected $jobManager;

    /**
     * @var int worker maximum runtime time in seconds
     */
    protected $workerMaxRuntime = 600;

    /**
     * @var int time to wait for a new job in seconds
     */
    protected $reserveTimeout = 60;

    /**
     * @DI\InjectParams({
     *      "jobManager" = @DI\Inject("lighthouse.core.job.manager"),
     *      "workerMaxRuntime" = @DI\Inject("%lighthouse.core.job.worker.max_runtime%"),
     *      "reserveTimeout" = @DI\Inject("%lighthouse.core.job.worker.reserve_timeout%")
     * })
     * @param \Lighthouse\CoreBundle\Job\JobManager $jobManager
     * @param int $workerMaxRuntime
     * @param int $reserveTimeout
     */
    public function __construct(JobManager $jobManager, $workerMaxRuntime, $reserveTimeout)
    {
        parent::__construct();

        $this->jobManager = $jobManager;
        $this->workerMaxRuntime = $workerMaxRuntime;
        $this->reserveTimeout = $reserveTimeout;
    }

    protected function configure()
    {
        $this->setName('lighthouse:worker');
        $this->addOption(
            'max-runtime',
            null,
            InputOption::VALUE_OPTIONAL,
            'Max runtime in seconds',
            $this->workerMaxRuntime
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $maxRuntime = $input->getOption('max-runtime');
        $runtimeDeadline = time() + $maxRuntime;

        $this->jobManager->startWatchingTubes();

        while ($runtimeDeadline > time()) {

            $output->writeln('<info>Waiting for the job.</info>');

            try {
                $job = $this->jobManager->reserveJob();

                if ($job) {
                    $output->writeln(
                        sprintf(
                            'Reserved job <info>%s #%s</info>, tube id #%s</info>',
                            $job->getType(),
                            $job->id,
                            $job->jobId
                        )
                    );

                    $this->jobManager->processJob($job);

                    $output->writeln(sprintf('Processed job <info>#%s</info>', $job->id));
                } else {
                    $output->writeln('<info>No jobs reserved. Trying one more time.</info>');
                }
            } catch (NotFoundJobException $e) {
                $output->writeln((string) $e);
            }
        }

        $output->writeln(sprintf('<info>Max runtime exceeded of %s seconds. Quiting.</info>', $maxRuntime));

        return 0;
    }
}
