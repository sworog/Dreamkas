<?php

namespace Lighthouse\JobBundle\QueueCommand\Reply;

use Pheanstalk_PheanstalkInterface as PheanstalkInterface;

class Replier
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
     * @param Reply $reply
     * @return Reply
     */
    public function doReply(Reply $reply)
    {
        $jobId = $this->pheanstalk->putInTube($this->tubeName, $reply);
        $reply->setJobId($jobId);
        return $reply;
    }

    /**
     * @param int $status
     * @param string $data
     * @return Reply
     */
    public function reply($status, $data = '')
    {
        return $this->doReply(new Reply($status, $data));
    }
}
