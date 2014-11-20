<?php

namespace Lighthouse\JobBundle\Tests\Command;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\JobBundle\QueueCommand\Client\ClientRequest;
use Lighthouse\JobBundle\QueueCommand\Status;

class QueueCommandTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Failed to execute command, no final status
     */
    public function testClientRequestList()
    {
        $client = $this->getContainer()->get('lighthouse.job.queue.command.client');

        $request = new ClientRequest('lighthouse:user:create');
        $request->setOnStatusCallback(function (Status $status) {
            echo sprintf('%s', $status->getData());
        });

        $exitCode = $client->execute($request, 1);
    }
}
