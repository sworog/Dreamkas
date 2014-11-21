<?php

namespace Lighthouse\JobBundle\Tests\Command;

use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\JobBundle\Command\QueueCommand;
use Lighthouse\JobBundle\QueueCommand\Client\ClientRequest;
use Lighthouse\JobBundle\QueueCommand\Reply\Reply;
use Pheanstalk_Job as Job;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_Matcher_Invocation as InvocationMatcher;

class QueueCommandTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        $this->clearMongoDb();
    }

    /**
     * @param PheanstalkInterface $pheanstalkMock
     * @return ApplicationTester
     */
    protected function createQueueCommandTester(PheanstalkInterface $pheanstalkMock)
    {
        $kernel = $this->getContainer()->get('kernel');
        $command = new QueueCommand($pheanstalkMock, $kernel);

        $application = $this->createConsoleApplication(false, true);

        $tester = new ApplicationTester($application);
        // run help command to register all command, in other case manually added command will be overridden
        $tester->runCommand('help');

        $application->add($command);

        return $tester;
    }

    /**
     * @param ApplicationTester $tester
     * @param PheanstalkInterface|MockObject $pheanstalkMock
     * @param InvocationMatcher $putInTubeMatcher
     * @param string $commandInput
     * @param string $replyTo
     * @return array statuses
     */
    protected function executeCommand(
        ApplicationTester $tester,
        MockObject $pheanstalkMock,
        InvocationMatcher $putInTubeMatcher,
        $commandInput,
        $replyTo
    ) {
        $request = new ClientRequest($commandInput, $replyTo);
        $job = new Job(1, $request->toString());
        $replies = array();

        $pheanstalkMock
            ->expects($this->at(0))
            ->method('watch')
            ->with($this->equalTo('command'));

        $pheanstalkMock
            ->expects($this->exactly(3))
            ->method('reserve')
            ->willReturnOnConsecutiveCalls(
                false,
                $job,
                false
            );

        $pheanstalkMock
            ->expects($putInTubeMatcher)
            ->method('putInTube')
            ->with(
                $this->equalTo($replyTo),
                $this->callback(
                    function ($data) use (&$replies) {
                        $replies[] = $data;
                        return true;
                    }
                )
            );

        $pheanstalkMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->isInstanceOf('\Pheanstalk_Job'));

        $tester->runCommand('lighthouse:queue:command', array('--max-tries' => 3));

        return $replies;
    }

    /**
     * @param string $commandInput
     * @param string $replyTo
     */
    public function testHelpCommand($commandInput = 'help', $replyTo = 'reply_1')
    {
        $pheanstalkMock = $this->getPheanstalkMock();

        $tester = $this->createQueueCommandTester($pheanstalkMock);

        $replies = $this->executeCommand(
            $tester,
            $pheanstalkMock,
            $this->atLeast(3),
            $commandInput,
            $replyTo
        );

        $this->assertSame(0, $tester->getStatusCode());

        $display = $tester->getDisplay();

        $expectedDisplay = <<<EOF
No queue command jobs found
Got the job 1: {"command":"{$commandInput}","replyTo":"{$replyTo}"}
No queue command jobs found

EOF;

        $this->assertEquals($expectedDisplay, $display);

        $firstReply = array_shift($replies);
        $lastReply = array_pop($replies);

        $this->assertReply($firstReply, Reply::STATUS_STARTED, '');
        $this->assertReply($lastReply, Reply::STATUS_FINISHED, 0);

        foreach ($replies as $reply) {
            $this->assertReply($reply, Reply::STATUS_PROCESSING);
        }
    }

    /**
     * @param string $commandInput
     * @param string $replyTo
     */
    public function testUserCreateNotEnoughArguments($commandInput = 'lighthouse:user:create', $replyTo = 'reply_1')
    {
        $pheanstalkMock = $this->getPheanstalkMock();

        $tester = $this->createQueueCommandTester($pheanstalkMock);

        $replies = $this->executeCommand(
            $tester,
            $pheanstalkMock,
            $this->exactly(2),
            $commandInput,
            $replyTo
        );

        $display = $tester->getDisplay();

        $expectedDisplay = <<<EOF
No queue command jobs found
Got the job 1: {"command":"{$commandInput}","replyTo":"{$replyTo}"}
No queue command jobs found

EOF;

        $this->assertEquals($expectedDisplay, $display);

        $firstReply = array_shift($replies);
        $lastReply = array_pop($replies);

        $this->assertReply($firstReply, Reply::STATUS_STARTED, '');
        $this->assertReply($lastReply, Reply::STATUS_FAILED, 'Not enough arguments.');

        $this->assertCount(0, $replies, 'No progress status should be found');
    }

    public function testUserCreate()
    {
        $commandInput = 'lighthouse:user:create queue@lighthouse.pro s3cr3t';
        $replyTo = 'reply_1';

        $pheanstalkMock = $this->getPheanstalkMock();

        $tester = $this->createQueueCommandTester($pheanstalkMock);

        $replies = $this->executeCommand(
            $tester,
            $pheanstalkMock,
            $this->atLeast(3),
            $commandInput,
            $replyTo
        );

        $display = $tester->getDisplay();

        $expectedDisplay = <<<EOF
No queue command jobs found
Got the job 1: {"command":"{$commandInput}","replyTo":"{$replyTo}"}
No queue command jobs found

EOF;

        $this->assertEquals($expectedDisplay, $display);

        $firstReply = array_shift($replies);
        $lastReply = array_pop($replies);

        $this->assertReply($firstReply, Reply::STATUS_STARTED, '');
        $this->assertReply($lastReply, Reply::STATUS_FINISHED, 0);

        $this->assertCount(3, $replies);
        $this->assertReply($replies[0], Reply::STATUS_PROCESSING, 'Creating user...');
        $this->assertReply($replies[1], Reply::STATUS_PROCESSING, "Done\n");
        $this->assertReply($replies[2], Reply::STATUS_PROCESSING);
    }

    /**
     * @param string $commandInput
     * @param string $replyTo
     */
    public function testInvalidCommand($commandInput = 'invalid', $replyTo = 'reply_invalid')
    {
        $pheanstalkMock = $this->getPheanstalkMock();

        $tester = $this->createQueueCommandTester($pheanstalkMock);

        $replies = $this->executeCommand(
            $tester,
            $pheanstalkMock,
            $this->exactly(2),
            $commandInput,
            $replyTo
        );

        $display = $tester->getDisplay();

        $expectedDisplay = <<<EOF
No queue command jobs found
Got the job 1: {"command":"{$commandInput}","replyTo":"{$replyTo}"}
No queue command jobs found

EOF;

        $this->assertEquals($expectedDisplay, $display);

        $firstReply = array_shift($replies);
        $lastReply = array_pop($replies);

        $this->assertReply($firstReply, Reply::STATUS_STARTED, '');
        $this->assertReply($lastReply, Reply::STATUS_FAILED, "Command \"{$commandInput}\" is not defined.");

        $this->assertCount(0, $replies, 'No progress status should be found');
    }

    /**
     * @param string $json
     * @param int $expectedStatus
     * @param string|null $expectedData
     */
    public function assertReply($json, $expectedStatus, $expectedData = null)
    {
        $reply = json_decode($json, true);
        $this->assertInternalType('array', $reply);
        $this->assertArrayHasKey('status', $reply);
        $this->assertArrayHasKey('data', $reply);
        $this->assertSame($expectedStatus, $reply['status'], 'Reply status does not match');
        if (null !== $expectedData) {
            $this->assertSame($expectedData, $reply['data'], 'Reply data does not match');
        }
    }

    /**
     * @return \Pheanstalk_PheanstalkInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPheanstalkMock()
    {
        return $this->getMockBuilder('\Pheanstalk_PheanstalkInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
