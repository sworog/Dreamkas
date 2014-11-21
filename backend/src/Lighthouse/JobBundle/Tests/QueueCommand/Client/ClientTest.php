<?php

namespace Lighthouse\JobBundle\Tests\QueueCommand\Client;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\JobBundle\QueueCommand\Client\ClientRequest;

class ClientTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Failed to execute command, no final status
     */
    public function testNoReply()
    {
        $client = $this->getContainer()->get('lighthouse.job.queue.command.client');

        $request = new ClientRequest('lighthouse:user:create');

        $client->execute($request, 0);
    }
}
