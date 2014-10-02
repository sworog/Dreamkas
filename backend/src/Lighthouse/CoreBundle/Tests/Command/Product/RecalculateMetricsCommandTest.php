<?php

namespace Lighthouse\CoreBundle\Tests\Command\Product;

use Lighthouse\CoreBundle\Command\Products\RecalculateMetricsCommand;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator;
use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RecalculateMetricsCommandTest extends TestCase
{
    public function testExecute()
    {
        /* @var StoreProductMetricsCalculator|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this
            ->getMockBuilder('Lighthouse\\CoreBundle\\Document\\Product\\Store\\StoreProductMetricsCalculator')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->exactly(2))
            ->method('recalculateAveragePrice');
        $mock
            ->expects($this->exactly(2))
            ->method('recalculateDailyAverageSales');

        $projectContextMock = $this
            ->getMockBuilder('Lighthouse\CoreBundle\Security\Project\ProjectContext')
            ->disableOriginalConstructor()
            ->getMock();

        $projectContextMock
            ->expects($this->exactly(2))
            ->method('authenticate');

        $project1Mock = new Project();
        $project1Mock->id = 'id1';
        $project2Mock = new Project();
        $project2Mock->id = 'id2';
        $projectContextMock
            ->method('getAllProjects')
            ->will($this->returnValue(array($project1Mock, $project2Mock)));

        $command = new RecalculateMetricsCommand($mock, $projectContextMock);

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate started', $commandTester->getDisplay());
        $this->assertContains('Recalculate metrics for project id1', $commandTester->getDisplay());
        $this->assertContains('Recalculate metrics for project id2', $commandTester->getDisplay());
        $this->assertContains('Recalculate finished', $commandTester->getDisplay());
    }
}
