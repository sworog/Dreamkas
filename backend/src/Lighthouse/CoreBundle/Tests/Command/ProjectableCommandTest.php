<?php

namespace Lighthouse\CoreBundle\Tests\Command;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class ProjectableCommandTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Project with name "aaa" not found
     */
    public function testInvalidProject()
    {
        $this->factory()->user()->getProject();

        $arrayInput = array(
            'command' => 'lighthouse:import:sales:local',
            '--project' => 'aaa'
        );

        $application = new Application(static::createKernel());
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $tester = new ApplicationTester($application);
        $tester->run($arrayInput);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Required option --project is missing
     */
    public function testMissingProjectOption()
    {
        $this->factory()->user()->getProject();

        $arrayInput = array(
            'command' => 'lighthouse:import:sales:local',
        );

        $application = new Application(static::createKernel());
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $tester = new ApplicationTester($application);
        $tester->run($arrayInput);
    }
}
