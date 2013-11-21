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

        $containerMock
            ->expects($this->at(0))
            ->method('setParameter')
            ->with($this->equalTo('test.client.class'));

        $containerMock
            ->expects($this->at(1))
            ->method('setParameter')
            ->with($this->equalTo('lighthouse.core.job.tube.prefix'));

        $containerMock
            ->expects($this->at(2))
            ->method('setParameter')
            ->with($this->equalTo('lighthouse.core.job.worker.max_runtime'));

        $containerMock
            ->expects($this->at(3))
            ->method('setParameter')
            ->with($this->equalTo('lighthouse.core.job.worker.reserve_timeout'));

        $containerMock
            ->expects($this->at(4))
            ->method('setParameter')
            ->with($this->equalTo('lighthouse.core.precision.money'));

        $containerMock
            ->expects($this->at(5))
            ->method('setParameter')
            ->with($this->equalTo('lighthouse.core.precision.quantity'));

        $containerMock
            ->expects($this->at(6))
            ->method('setParameter')
            ->with($this->equalTo('lighthouse.core.rounding.default'));

        $extension = new LighthouseCoreExtension();
        $extension->load(array(), $containerMock);
    }
}
