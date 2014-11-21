<?php

namespace Lighthouse\JobBundle\QueueCommand\Client;

use Lighthouse\JobBundle\QueueCommand\Status;
use Pheanstalk_Job as Job;
use Closure;

class ClientRequest
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
    protected $onStatusCallback;

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
     * @param Status $status
     */
    public function onStatus(Status $status)
    {
        if ($this->onStatusCallback) {
            call_user_func($this->onStatusCallback, $status);
        }
    }

    /**
     * @param callable $callback
     */
    public function setOnStatusCallback(Closure $callback)
    {
        $this->onStatusCallback = $callback;
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
