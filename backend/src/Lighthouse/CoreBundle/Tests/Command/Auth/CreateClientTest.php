<?php

namespace Lighthouse\CoreBundle\Tests\Command\Auth;

use Lighthouse\CoreBundle\Command\Auth\CreateClient;
use Lighthouse\CoreBundle\Document\Auth\Client;
use Symfony\Component\Console\Tester\CommandTester;

class CreateClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider argumentsProvider
     * @param string $secret
     * @param string $publicId
     */
    public function testExecute($secret, $publicId)
    {
        $clientManagerMock = $this->getMock(
            'FOS\OAuthServerBundle\Model\ClientManagerInterface',
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
