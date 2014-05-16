<?php

namespace Lighthouse\CoreBundle\Tests\OpenStack\ObjectStore\Resource;

use Guzzle\Plugin\Mock\MockPlugin;
use Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource\Container;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class ContainerTest extends ContainerAwareTestCase
{
    public function testContainerDI()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.ok'));

        $client = $this->getContainer()->get('openstack.selectel');
        $client->addSubscriber($mockPlugin);

        /* @var Container $container */
        $container = $this->getContainer()->get('openstack.selectel.storage.container');
        $this->assertInstanceOf(
            'Lighthouse\\CoreBundle\\OpenStack\\ObjectStore\\Resource\\Container',
            $container
        );
        $this->assertEquals('cdn.lighthouse.pro', $container->getMetadata()->getProperty('domains'));

        $this->assertCount(2, $mockPlugin->getReceivedRequests());
    }
}
