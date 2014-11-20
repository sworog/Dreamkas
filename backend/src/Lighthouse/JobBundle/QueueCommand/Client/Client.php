<?php

namespace Lighthouse\JobBundle\QueueCommand\Client;

use Lighthouse\JobBundle\QueueCommand\Status;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.job.queue.command.client")
 */
class Client
{
    /**
     * @var PheanstalkInterface
     */
    protected $pheanstalk;

    /**
     * @var string
     */
    protected $commandTubeName = 'command';

    /**
     * @var int
     */
    protected $timeout = 1;

    /**
     * @DI\InjectParams({
     *      "pheanstalk" = @DI\Inject("leezy.pheanstalk")
     * })
     * @param PheanstalkInterface $pheanstalk
     */
    public function __construct(PheanstalkInterface $pheanstalk)
    {
        $this->pheanstalk = $pheanstalk;
    }

    /**
     * @param ClientRequest $request
     * @param int $maxRuntime
     * @return int final exitCode
     */
    public function execute(ClientRequest $request, $maxRuntime = 60)
    {
        $this->pheanstalk->putInTube($this->commandTubeName, $request->toString());

        $deadLineTime = time() + $maxRuntime;

        do {
            /* @var \Pheanstalk_Job $job */
            $job = $this->pheanstalk->reserveFromTube($request->getReplyTo(), 1);
            if ($job) {
                $status = Status::createFromJob($job);
                $request->onStatus($status);
                $this->pheanstalk->delete($job);

                if ($status->isFinished()) {
                    return $status->getData();
                }
            }

        } while (time() < $deadLineTime);

        throw new \RuntimeException('Failed to execute command, no final status');
    }
}
