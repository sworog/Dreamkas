<?php

namespace Lighthouse\CoreBundle\Tests\Command\Auth;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Lighthouse\CoreBundle\Command\Auth\CreateClient;
use Lighthouse\CoreBundle\Document\Auth\Client;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateClientTest extends TestCase
{
    /**
     * @dataProvider argumentsProvider
     * @param string $secret
     * @param string $publicId
     */
    public function testExecute($secret, $publicId)
    {
        /* @var ClientManagerInterface|\PHPUnit_Framework_MockObject_MockObject $clientManagerMock */
        $clientManagerMock = $this->getMock(
            'FOS\\OAuthServerBundle\\Model\\ClientManagerInterface',
            array(),
            array(),
            '',
            false
        );
        $clientManagerMock->expects($this->once())
            ->method('createClient')
            ->will($this->returnValue(new Client()));
        $clientManagerMock->expects($this->once())
            ->method('updateClient')
            ->will($this->returnValue(null));

        $command = new CreateClient();
        $command->setClientManager($clientManagerMock);

        $commandTester = new CommandTester($command);

        $input = array();
        if (null !== $secret) {
            $input['secret'] = $secret;
        }
        if (null !== $publicId) {
            $input['public-id'] = $publicId;
        }

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();
        $this->assertContains('Client created', $display);

        if ($secret) {
            $this->assertContains('Secret: ' . $secret, $display);
        }

        if ($publicId) {
            $this->assertContains('PublicID: ' . $publicId . '_' . $publicId, $display);
        }
    }

    /**
     * @return array
     */
    public function argumentsProvider()
    {
        return array(
            array(null, null),
            array('admin', null),
            array(null, 'secret'),
            array('admin', 'secret')
        );
    }
}
