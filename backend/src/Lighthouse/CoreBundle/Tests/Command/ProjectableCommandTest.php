<?php

namespace Lighthouse\CoreBundle\Tests\Command;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

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

        $tester = $this->createConsoleTester(false);
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

        $tester = $this->createConsoleTester(false);
        $tester->run($arrayInput);
    }
}
