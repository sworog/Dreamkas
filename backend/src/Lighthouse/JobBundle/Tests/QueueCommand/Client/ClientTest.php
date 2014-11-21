<?php

namespace Lighthouse\JobBundle\Tests\QueueCommand\Client;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\JobBundle\QueueCommand\Client\Client;
use Lighthouse\JobBundle\QueueCommand\Client\ClientRequest;
use Lighthouse\JobBundle\QueueCommand\Reply\Reply;
use Pheanstalk_Job as Job;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_Assert;

class ClientTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Failed to execute command, no final status
     */
    public function testNoReply()
    {
        $pheanstalk = $this->getPheanstalkMock();
        $client = new Client($pheanstalk);

        $request = new ClientRequest('lighthouse:user:create');

        $pheanstalk
            ->expects($this->once())
            ->method('putInTube')
            ->with('command', $request);

        $pheanstalk
            ->expects($this->once())
            ->method('reserveFromTube')
            ->willReturn(false);

        $client->execute($request, 0);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid reply json
     */
    public function testInvalidReply()
    {
        $pheanstalk = $this->getPheanstalkMock();
        $client = new Client($pheanstalk);

        $request = new ClientRequest('lighthouse:user:create');
        $job = new Job(10, 'invalid data');

        $pheanstalk
            ->expects($this->once())
            ->method('putInTube')
            ->with('command', $request);

        $pheanstalk
            ->expects($this->once())
            ->method('reserveFromTube')
            ->willReturn($job);

        $client->execute($request, 0);
    }

    public function testExecute()
    {
        $pheanstalk = $this->getPheanstalkMock();
        $client = new Client($pheanstalk);

        $replies = array();

        $request = new ClientRequest('lighthouse:user:create');
        $request->setOnStatusCallback(
            function (Reply $reply) use (&$replies) {
                $replies[] = $reply->toJson();
                PHPUnit_Framework_Assert::assertNotNull($reply->getJobId());
            }
        );

        $replyJob1 = new Job(10, (string) new Reply(Reply::STATUS_STARTED, ''));
        $replyJob2 = new Job(11, (string) new Reply(Reply::STATUS_PROCESSING, 'Processing'));
        $replyJob3 = new Job(12, (string) new Reply(Reply::STATUS_FINISHED, 0));

        $pheanstalk
            ->expects($this->once())
            ->method('putInTube')
            ->with('command', $request);

        $pheanstalk
            ->expects($this->exactly(7))
            ->method('reserveFromTube')
            ->willReturnOnConsecutiveCalls(false, $replyJob1, false, false, $replyJob2, false, $replyJob3);

        $pheanstalk
            ->expects($this->exactly(3))
            ->method('delete')
            ->with($this->isInstanceOf('\Pheanstalk_Job'));

        $exitCode = $client->execute($request, 60);

        $this->assertEquals(0, $exitCode);

        $expectedReplies = array(
            array('status' => Reply::STATUS_STARTED, 'data' => ''),
            array('status' => Reply::STATUS_PROCESSING, 'data' => 'Processing'),
            array('status' => Reply::STATUS_FINISHED, 'data' => 0),
        );
        $this->assertSame($expectedReplies, $replies);
    }

    /**
     * @return PheanstalkInterface|MockObject
     */
    protected function getPheanstalkMock()
    {
        return $this->getMockBuilder('\Pheanstalk_PheanstalkInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
