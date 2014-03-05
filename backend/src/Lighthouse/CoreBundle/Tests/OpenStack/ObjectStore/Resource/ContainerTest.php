<?php

namespace Lighthouse\CoreBundle\Tests\OpenStack\ObjectStore\Resource;

use Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource\Container;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class ContainerTest extends ContainerAwareTestCase
{
    /**
     * @group Functional
     */
    public function testContainerDI()
    {
        /* @var Container $container */
        $container = $this->getContainer()->get('openstack.selectel.storage.container');
        $this->assertInstanceOf(
            'Lighthouse\\CoreBundle\\OpenStack\\ObjectStore\\Resource\\Container',
            $container
        );
        $this->assertEquals('cdn.lighthouse.pro', $container->getMetadata()->getProperty('domains'));
    }
}
