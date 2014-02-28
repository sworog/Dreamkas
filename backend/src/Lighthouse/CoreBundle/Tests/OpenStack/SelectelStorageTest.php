<?php

namespace Lighthouse\CoreBundle\Tests\OpenStack;

use Guzzle\Http\Client;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Plugin\Mock\MockPlugin;
use Lighthouse\CoreBundle\OpenStack\SelectelStorage;
use Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource\Container;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use OpenCloud\Common\Service\CatalogItem;
use OpenCloud\ObjectStore\Service;

class SelectelStorageTest extends ContainerAwareTestCase
{
    public function testAuthenticationRequest()
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

    public function testAuthenticationToken()
    {
        $client = new SelectelStorage(
            'https://localhost',
            array(
                'username' => 'user',
                'password' => 'password',
            )
        );
        $this->mockRequests(
            $client,
            $this->getFixtureFilePath('OpenStack/auth.response.ok')
        );

        $client->authenticate();

        $token = $client->getTokenObject();
        $this->assertInstanceOf('OpenCloud\\Identity\\Resource\\Token', $token);
        $this->assertEquals('285a05936fe0817beac78e84ad2c5f12', $token->getId());
        $this->assertEquals('86029', $token->getExpires());
    }

    public function testAuthenticationCatalog()
    {
        $client = new SelectelStorage(
            'https://localhost',
            array(
                'username' => 'user',
                'password' => 'password',
            )
        );
        $this->mockRequests(
            $client,
            $this->getFixtureFilePath('OpenStack/auth.response.ok')
        );

        $client->authenticate();

        $catalog = $client->getCatalog();
        $this->assertInstanceOf('OpenCloud\\Common\\Service\\Catalog', $catalog);
        $items = $catalog->getItems();
        $this->assertCount(1, $items);
        /* @var CatalogItem $catalogItem */
        $catalogItem = reset($items);
        $this->assertInstanceOf('OpenCloud\\Common\\Service\\CatalogItem', $catalogItem);
        $this->assertEquals(SelectelStorage::DEFAULT_NAME, $catalogItem->getName());
        $this->assertEquals(Service::DEFAULT_TYPE, $catalogItem->getType());
        $endPoint = $catalogItem->getEndpointFromRegion(SelectelStorage::DEFAULT_REGION);
        $this->assertObjectHasAttribute('publicURL', $endPoint);
        $this->assertEquals('https://xxx.selcdn.ru/', $endPoint->publicURL);
        $this->assertObjectHasAttribute('region', $endPoint);
        $this->assertEquals(SelectelStorage::DEFAULT_REGION, $endPoint->region);
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

    /**
     * @group Functional
     */
    public function testContainerDI()
    {
        /* @var Container $container */
        $container = $this->getContainer()->get('openstack.selectel.storage.container');
        $container->retrieveMetadata();
        $this->assertEquals('cdn.lighthouse.pro', $container->getMetadata()->getProperty('domains'));
    }
}
