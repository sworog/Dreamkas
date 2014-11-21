<?php

namespace Lighthouse\JobBundle\QueueCommand;

use Pheanstalk_Job as Job;

class Status
{
    const STATUS_STARTED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_FINISHED = 3;
    const STATUS_FAILED = 4;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $jobId;

    /**
     * @param int $status
     * @param string $data
     */
    public function __construct($status, $data = '')
    {
        $this->status = $status;
        $this->data = $data;
    }

    /**
     * @param string $jobId
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return bool
     */
    public function isStatus($status)
    {
        return $status === $this->status;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->isStatus(self::STATUS_FINISHED) || $this->isStatus(self::STATUS_FAILED);
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function toJson()
    {
        return array(
            'status' => $this->getStatus(),
            'data' => $this->getData(),
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return json_encode($this->toJson());
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
     * @return Status
     */
    public static function createFromJson($json)
    {
        $data = json_decode($json, true);
        if (isset($data['status'], $data['data'])) {
            return new self($data['status'], $data['data']);
        }
        throw new \RuntimeException('Invalid status json');
    }

    /**
     * @param Job $job
     * @return Status
     */
    public static function createFromJob(Job $job)
    {
        $status = self::createFromJson($job->getData());
        $status->setJobId($job->getId());
        return $status;
    }
}
