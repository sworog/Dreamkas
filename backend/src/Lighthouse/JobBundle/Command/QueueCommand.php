<?php

namespace Lighthouse\JobBundle\Command;

use Lighthouse\JobBundle\QueueCommand\Client\ClientRequest;
use Lighthouse\JobBundle\QueueCommand\Console\Input;
use Lighthouse\JobBundle\QueueCommand\Console\Output;
use Lighthouse\JobBundle\QueueCommand\Reply\Reply;
use Lighthouse\JobBundle\QueueCommand\Reply\Replier;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;
use Pheanstalk_Job as Job;
use LighthouseKernel;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.job.queue.command")
 * @DI\Tag("console.command")
 */
class QueueCommand extends Command
{
    /**
     * @var PheanstalkInterface;
     */
    protected $pheanstalk;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var int
     */
    protected $reserveTimeout = 1;

    /**
     * @var string
     */
    protected $commandTube = 'command';

    /**
     * @DI\InjectParams({
     *      "pheanstalk" = @DI\Inject("leezy.pheanstalk"),
     *      "kernel" = @DI\Inject("kernel")
     * })
     * @param PheanstalkInterface $pheanstalk
     * @param KernelInterface $kernel
     */
    public function __construct(PheanstalkInterface $pheanstalk, KernelInterface $kernel)
    {
        parent::__construct();

        $this->pheanstalk = $pheanstalk;
        $this->kernel = $kernel;
    }

    protected function configure()
    {
        $this->setName('lighthouse:queue:command');
        $this->addOption('max-runtime', null, InputOption::VALUE_OPTIONAL, 'Max runtime in seconds', 60);
        $this->addOption('max-tries', null, InputOption::VALUE_OPTIONAL, 'Max reserve job tries', null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $maxRuntime = $input->getOption('max-runtime');
        $maxTries = $input->getOption('max-tries');
        $runtimeDeadline = time() + $maxRuntime;

        $this->pheanstalk->watch($this->commandTube);

        $tries = 0;
        do {
            $tries++;
            /* @var Job $job */
            $job = $this->pheanstalk->reserve($this->reserveTimeout);
            if ($job) {
                $output->writeln(sprintf('Got the job %s: %s', $job->getId(), $job->getData()));
                $this->processJob($job, $output);
                $this->pheanstalk->delete($job);
            } else {
                $output->writeln('No queue command jobs found');
            }
        } while ($runtimeDeadline > time() && (null === $maxTries || $maxTries > $tries));
        return 0;
    }

    /**
     * @param Job $job
     * @param OutputInterface $output
     * @return int
     */
    protected function processJob(Job $job, OutputInterface $output)
    {
        $request = ClientRequest::createFromJob($job);

        $replier = new Replier($this->pheanstalk, $request->getReplyTo());

        $replier->reply(Reply::STATUS_STARTED);

        $application = $this->createApplication();

        $input = new Input($request->getCommand());
        $output = new Output($replier);

        try {
            $data = $application->run($input, $output);
            $status = Reply::STATUS_FINISHED;
        } catch (\Exception $e) {
            $data = $e->getMessage();
            $status = Reply::STATUS_FAILED;
        }

        $replier->reply($status, $data);

        return $status;
    }

    /**
     * @return Application
     */
    protected function createApplication()
    {
        $kernel = new LighthouseKernel(
            $this->kernel->getEnvironment(),
            $this->kernel->isDebug()
        );

        $application = new Application($kernel);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        return $application;
    }
}
