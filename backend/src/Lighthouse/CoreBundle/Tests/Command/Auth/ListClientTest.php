<?php

namespace Lighthouse\CoreBundle\Tests\Command\Auth;

use Doctrine\Common\Persistence\ObjectRepository;
use Lighthouse\CoreBundle\Command\Auth\ListClient;
use Lighthouse\CoreBundle\Document\Auth\Client;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ListClientTest extends TestCase
{
    public function testExecute()
    {
        /* @var ObjectRepository|\PHPUnit_Framework_MockObject_MockObject $clientRepositoryMock*/
        $clientRepositoryMock = $this->getMock(
            'Doctrine\\Common\\Persistence\\ObjectRepository',
            array(),
            array(),
            '',
            false
        );

        $clients = array();
        $client1 = new Client();
        $client1->setId('test');
        $client1->setRandomId('random');
        $client1->setSecret('secret');
        $clients[] = $client1;

        $client2 = new Client();
        $client2->setId('android');
        $client2->setRandomId('99754106633f94d350db34d548d6091a');
        $client2->setSecret('password');
        $clients[] = $client2;

        $clientRepositoryMock->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($clients));

        $command = new ListClient();
        $command->setClientRepository($clientRepositoryMock);

        $commandTester = new CommandTester($command);

        $exitCode = $commandTester->execute(array());

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();
        $this->assertContains('Client PublicID: test_random, Secret: secret', $display);
        $this->assertContains('Client PublicID: android_99754106633f94d350db34d548d6091a, Secret: password', $display);
    }
}
