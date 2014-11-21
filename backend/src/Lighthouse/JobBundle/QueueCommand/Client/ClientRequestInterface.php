<?php

namespace Lighthouse\JobBundle\QueueCommand\Client;

use Lighthouse\JobBundle\QueueCommand\Reply\Reply;

interface ClientRequestInterface
{
    /**
     * @return string
     */
    public function getReplyTo();

    /**
     * @param Reply $reply
     */
    public function onReply(Reply $reply);

    /**
     * @return string
     */
    public function __toString();
}
