<?php

namespace Lighthouse\JobBundle\QueueCommand\Client;

use Lighthouse\JobBundle\QueueCommand\Reply\Reply;
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
     * @param ClientRequestInterface $request
     * @param int $maxRuntime
     * @return int final exitCode
     */
    public function execute(ClientRequestInterface $request, $maxRuntime = 60)
    {
        $this->pheanstalk->putInTube($this->commandTubeName, (string) $request);

        $deadLineTime = time() + $maxRuntime;

        do {
            /* @var \Pheanstalk_Job $job */
            $job = $this->pheanstalk->reserveFromTube($request->getReplyTo(), $this->timeout);
            if ($job) {
                $reply = Reply::createFromJob($job);
                $request->onReply($reply);
                $this->pheanstalk->delete($job);

                if ($reply->isFinished()) {
                    return $reply->getData();
                }
            }

        } while (time() < $deadLineTime);

        throw new \RuntimeException('Failed to execute command, no final status');
    }
}
