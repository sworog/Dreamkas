<?php

namespace Lighthouse\JobBundle\Tests\Command;

use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\JobBundle\Command\QueueCommand;
use Lighthouse\JobBundle\QueueCommand\Client\ClientRequest;
use Lighthouse\JobBundle\QueueCommand\Status;
use Pheanstalk_Job as Job;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class QueueCommandTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        $this->clearMongoDb();
    }

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

        $client->execute($request, 1);
    }

    /**
     * @param \Pheanstalk_PheanstalkInterface $pheanstalkMock
     * @return ApplicationTester
     */
    protected function createQueueCommandTester($pheanstalkMock)
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
     * @param \Pheanstalk_PheanstalkInterface|MockObject $pheanstalkMock
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $putInTubeMatcher
     * @param string $commandInput
     * @param string $replyTo
     * @return array statuses
     */
    protected function executeCommand(
        ApplicationTester $tester,
        MockObject $pheanstalkMock,
        \PHPUnit_Framework_MockObject_Matcher_Invocation $putInTubeMatcher,
        $commandInput,
        $replyTo
    ) {
        $request = new ClientRequest($commandInput, $replyTo);
        $job = new Job(1, $request->toString());
        $statuses = array();

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
                    function ($data) use (&$statuses) {
                        $statuses[] = $data;
                        return true;
                    }
                )
            );

        $pheanstalkMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->isInstanceOf('\Pheanstalk_Job'));

        $tester->runCommand('lighthouse:queue:command', array('--max-tries' => 3));

        return $statuses;
    }

    /**
     * @param string $commandInput
     * @param string $replyTo
     */
    public function testHelpCommand($commandInput = 'help', $replyTo = 'reply_1')
    {
        $pheanstalkMock = $this->getPheanstalkMock();

        $tester = $this->createQueueCommandTester($pheanstalkMock);

        $request = new ClientRequest($commandInput, $replyTo);
        $job = new Job(1, $request->toString());
        $statuses = array();

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
            ->expects($this->atLeast(3))
            ->method('putInTube')
            ->with(
                $this->equalTo($replyTo),
                $this->callback(
                    function ($data) use (&$statuses) {
                        $statuses[] = $data;
                        return true;
                    }
                )
            );

        $pheanstalkMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->isInstanceOf('\Pheanstalk_Job'));

        $tester->runCommand('lighthouse:queue:command', array('--max-tries' => 3));

        $this->assertSame(0, $tester->getStatusCode());

        $display = $tester->getDisplay();

        $expectedDisplay = <<<EOF
No queue command jobs found
Got the job 1: {"command":"{$commandInput}","replyTo":"{$replyTo}"}
No queue command jobs found

EOF;

        $this->assertEquals($expectedDisplay, $display);

        $firstStatus = array_shift($statuses);
        $lastStatus = array_pop($statuses);

        $this->assertStatus($firstStatus, Status::STATUS_STARTED, '');
        $this->assertStatus($lastStatus, Status::STATUS_FINISHED, 0);

        foreach ($statuses as $status) {
            $this->assertStatus($status, Status::STATUS_PROCESSING);
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

        $statuses = $this->executeCommand(
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

        $firstStatus = array_shift($statuses);
        $lastStatus = array_pop($statuses);

        $this->assertStatus($firstStatus, Status::STATUS_STARTED, '');
        $this->assertStatus($lastStatus, Status::STATUS_FAILED, 'Not enough arguments.');

        $this->assertCount(0, $statuses, 'No progress status should be found');
    }

    public function testUserCreate()
    {
        $commandInput = 'lighthouse:user:create queue@lighthouse.pro s3cr3t';
        $replyTo = 'reply_1';

        $pheanstalkMock = $this->getPheanstalkMock();

        $tester = $this->createQueueCommandTester($pheanstalkMock);

        $statuses = $this->executeCommand(
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

        $firstStatus = array_shift($statuses);
        $lastStatus = array_pop($statuses);

        $this->assertStatus($firstStatus, Status::STATUS_STARTED, '');
        $this->assertStatus($lastStatus, Status::STATUS_FINISHED, 0);

        $this->assertCount(3, $statuses);
        $this->assertStatus($statuses[0], Status::STATUS_PROCESSING, 'Creating user...');
        $this->assertStatus($statuses[1], Status::STATUS_PROCESSING, "Done\n");
        $this->assertStatus($statuses[2], Status::STATUS_PROCESSING);
    }

    /**
     * @param string $commandInput
     * @param string $replyTo
     */
    public function testInvalidCommand($commandInput = 'invalid', $replyTo = 'reply_invalid')
    {
        $pheanstalkMock = $this->getPheanstalkMock();

        $tester = $this->createQueueCommandTester($pheanstalkMock);

        $statuses = $this->executeCommand(
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

        $firstStatus = array_shift($statuses);
        $lastStatus = array_pop($statuses);

        $this->assertStatus($firstStatus, Status::STATUS_STARTED, '');
        $this->assertStatus($lastStatus, Status::STATUS_FAILED, "Command \"{$commandInput}\" is not defined.");

        $this->assertCount(0, $statuses, 'No progress status should be found');
    }

    /**
     * @param string $json
     * @param int $expectedStatus
     * @param string|null $expectedData
     */
    public function assertStatus($json, $expectedStatus, $expectedData = null)
    {
        $decoded = json_decode($json, true);
        $this->assertInternalType('array', $decoded);
        $this->assertArrayHasKey('status', $decoded);
        $this->assertArrayHasKey('data', $decoded);
        $this->assertSame($expectedStatus, $decoded['status'], 'Status does not match');
        if (null !== $expectedData) {
            $this->assertSame($expectedData, $decoded['data'], 'Data does not match');
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
