<?php

namespace Lighthouse\CoreBundle\Tests\OpenStack;

use Guzzle\Http\Client;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Plugin\Mock\MockPlugin;
use Lighthouse\CoreBundle\OpenStack\SelectelStorage;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class SelectelStorageTest extends ContainerAwareTestCase
{
    public function testAuthentication()
    {
        $user = 'user';
        $key = 'password';

        $client = new SelectelStorage(
            'https://localhost',
            array(
                'username' => $user,
                'password' => $key,
            )
        );
        $mockPlugin = $this->mockRequests(
            $client,
            $this->getFixtureFilePath('OpenStack/auth.response.ok')
        );

        $client->authenticate();

        /* @var EntityEnclosingRequestInterface[] $requests */
        $requests = $mockPlugin->getReceivedRequests();
        $this->assertCount(1, $requests);
        $authRequest = $requests[0];
        $this->assertTrue($authRequest->hasHeader('x-auth-user'));
        $this->assertEquals('user', $authRequest->getHeader('x-auth-user'));
        $this->assertTrue($authRequest->hasHeader('X-auth-key'));
        $this->assertEquals('password', $authRequest->getHeader('x-auth-key'));
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CredentialError
     * @expectedExceptionMessage Unrecognized credential secret
     */
    public function testMissingCredentials()
    {
        $client = new SelectelStorage('https://localhost', array());
        $client->authenticate();
    }

    /**
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     * @expectedExceptionMessage [status code] 403
     */
    public function testFailedAuthentication()
    {
        $client = new SelectelStorage(
            'https://localhost',
            array(
                'username' => 'user',
                'password' => 'incorrect'
            )
        );

        $this->mockRequests(
            $client,
            $this->getFixtureFilePath('OpenStack/auth.response.forbidden')
        );

        $client->authenticate();
    }

    /**
     * @param Client $client
     * @param array|string $responses
     * @return MockPlugin
     */
    protected function mockRequests(Client $client, $responses)
    {
        $mockPlugin = new MockPlugin();
        foreach ((array) $responses as $response) {
            $mockPlugin->addResponse($response);
        }
        $client->addSubscriber($mockPlugin);
        return $mockPlugin;
    }
}
