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
     * @param Reply $status
     */
    public function onStatus(Reply $status);

    /**
     * @return string
     */
    public function __toString();
}
