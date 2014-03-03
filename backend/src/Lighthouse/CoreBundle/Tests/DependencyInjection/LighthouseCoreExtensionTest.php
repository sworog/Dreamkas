<?php

namespace Lighthouse\CoreBundle\Tests\DependencyInjection;

use Lighthouse\CoreBundle\DependencyInjection\LighthouseCoreExtension;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LighthouseCoreExtensionTest extends TestCase
{
    public function testLoad()
    {
        /* @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject $containerMock */
        $containerMock = $this->getMock(
            'Symfony\\Component\\DependencyInjection\\ContainerBuilder',
            array('setParameter')
        );

        $capturedArguments = array();
        $capture = function ($argument) use (&$capturedArguments) {
            $capturedArguments[] = $argument;
        };

        $containerMock
            ->expects($this->any())
            ->method('setParameter')
            ->will($this->returnCallback($capture));

        $extension = new LighthouseCoreExtension();
        $extension->load(array(), $containerMock);

        $expectedValues = array(
            'test.client.class',
            'lighthouse.core.job.tube.prefix',
            'lighthouse.core.job.worker.max_runtime',
            'lighthouse.core.job.worker.reserve_timeout',
            'lighthouse.core.precision.money',
            'lighthouse.core.precision.quantity',
            'lighthouse.core.rounding.default',
            'openstack.selectel.auth_url',
            'openstack.selectel.secret.username',
            'openstack.selectel.secret.password',
            'openstack.selectel.secret',
            'openstack.selectel.options',
            'openstack.selectel.storage.container.name',
        );
        $this->assertEquals($capturedArguments, $expectedValues, '', 0, 10, true);
    }
}
