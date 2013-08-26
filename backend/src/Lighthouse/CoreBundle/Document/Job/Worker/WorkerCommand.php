<?php

namespace Lighthouse\CoreBundle\Document\Job\Worker;

use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Document\Job\JobManager;
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
     * @DI\InjectParams({
     *      "jobManager" = @DI\Inject("lighthouse.core.job.manager")
     * })
     * @param JobManager $jobManager
     */
    public function __construct(JobManager $jobManager)
    {
        parent::__construct();

        $this->jobManager = $jobManager;
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

        $this->jobManager->startWatchingTubes();

        while ($runtimeDeadline > time()) {

            $output->writeln('<info>Waiting for the job..</info>');

            try {
                $job = $this->jobManager->reserveJob();

                if ($job) {
                    $output->writeln(sprintf('<info>Reserved job #%s</info>', $job->id));

                    $this->jobManager->processJob($job);

                    $output->writeln(sprintf('<info>Processed job #%s</info>', $job->id));
                } else {
                    $output->writeln('<info>No jobs reserved. Trying one more time.</info>');
                }
            } catch (NotFoundJobException $e) {
                $output->writeln((string) $e);
            }
        }

        $output->writeln('<info>Max execution time exceeded. Quiting.</info>');

        return 0;
    }
}
