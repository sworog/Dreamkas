<?php

namespace Lighthouse\CoreBundle\Tests\Command\Auth;

use Lighthouse\CoreBundle\Command\Auth\CreateClient;
use Lighthouse\CoreBundle\Command\Auth\ListClient;
use Lighthouse\CoreBundle\Document\Auth\Client;
use Lighthouse\CoreBundle\Test\TestCase;
use OAuth2\OAuth2;
use Symfony\Component\Console\Tester\CommandTester;

class ListClientTest extends TestCase
{
    public function testExecute()
    {
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
        $client2->setId('webbo');
        $client2->setRandomId('dwd4324234jkn23j42');
        $client2->setSecret('retsec');
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
        $this->assertContains('Client PublicID: webbo_dwd4324234jkn23j42, Secret: retsec', $display);
    }
}
