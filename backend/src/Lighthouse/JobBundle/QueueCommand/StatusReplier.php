<?php

namespace Lighthouse\JobBundle\QueueCommand;

use Pheanstalk_PheanstalkInterface as PheanstalkInterface;

class StatusReplier
{
    /**
     * @var PheanstalkInterface
     */
    protected $pheanstalk;

    /**
     * @var string
     */
    protected $tubeName;

    /**
     * @param PheanstalkInterface $pheanstalk
     * @param string $tubeName
     */
    public function __construct(PheanstalkInterface $pheanstalk, $tubeName)
    {
        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $tubeName;
    }

    /**
     * @param Status $status
     * @return Status
     */
    public function doSendStatus(Status $status)
    {
        $jobId = $this->pheanstalk->putInTube($this->tubeName, $status);
        $status->setJobId($jobId);
        return $status;
    }

    /**
     * @param int $status
     * @param string $data
     * @return Status
     */
    public function sendStatus($status, $data = '')
    {
        return $this->doSendStatus(new Status($status, $data));
    }
}
