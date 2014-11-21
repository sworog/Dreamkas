<?php

namespace Lighthouse\JobBundle\QueueCommand\Client;

use Lighthouse\JobBundle\QueueCommand\Reply\Reply;
use Pheanstalk_Job as Job;
use Closure;

class ClientRequest implements ClientRequestInterface
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $replyTo;

    /**
     * @var Closure
     */
    protected $onReplyCallback;

    /**
     * @param string $command
     * @param string $replyTo
     */
    public function __construct($command, $replyTo = null)
    {
        $this->setCommand($command);

        if (null !== $replyTo) {
            $this->setReplyTo($replyTo);
        }
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $replyTo
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        if (null === $this->replyTo) {
            $this->generateReplyTo();
        }
        return $this->replyTo;
    }

    /**
     * @param string $prefix
     * @return string
     */
    public function generateReplyTo($prefix = 'reply')
    {
        $replyTo = uniqid($prefix, true);
        $this->setReplyTo($replyTo);
        return $replyTo;
    }

    /**
     * @param Reply $reply
     */
    public function onReply(Reply $reply)
    {
        if ($this->onReplyCallback) {
            call_user_func($this->onReplyCallback, $reply);
        }
    }

    /**
     * @param callable $callback
     */
    public function setOnReplyCallback(Closure $callback)
    {
        $this->onReplyCallback = $callback;
    }

    /**
     * @return array
     */
    public function toJson()
    {
        return array(
            'command' => $this->getCommand(),
            'replyTo' => $this->getReplyTo(),
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return json_encode($this->toJson(), true);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param string $json
     * @return ClientRequest
     */
    public static function createFromJson($json)
    {
        $data = json_decode($json, true);
        if (isset($data['command'], $data['replyTo'])) {
            return new self($data['command'], $data['replyTo']);
        }
        throw new \RuntimeException("Failed to create request from json " . $json);
    }

    /**
     * @param Job $job
     * @return ClientRequest
     */
    public static function createFromJob(Job $job)
    {
        return self::createFromJson($job->getData());
    }
}
