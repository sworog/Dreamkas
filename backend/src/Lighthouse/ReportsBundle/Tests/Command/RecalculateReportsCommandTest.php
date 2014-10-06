<?php

namespace Lighthouse\ReportsBundle\Tests\Command;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\ReportsBundle\Command\RecalculateReportsCommand;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RecalculateReportsCommandTest extends TestCase
{
    public function testExecute()
    {
        /* @var GrossSalesReportManager|\PHPUnit_Framework_MockObject_MockObject $grossSalesManagerMock */
        $grossSalesManagerMock = $this
            ->getMockBuilder('Lighthouse\\ReportsBundle\\Reports\\GrossSales\\GrossSalesReportManager')
            ->disableOriginalConstructor()
            ->getMock();

        $grossSalesManagerMock
            ->expects($this->exactly(0))
            ->method('recalculateStoreGrossSalesReport');
        $grossSalesManagerMock
            ->expects($this->exactly(0))
            ->method('recalculateGrossSalesProductReport');

        /* @var GrossMarginManager|\PHPUnit_Framework_MockObject_MockObject $grossMarginManagerMock */
        $grossMarginManagerMock = $this
            ->getMockBuilder('Lighthouse\\ReportsBundle\\Reports\\GrossMargin\\GrossMarginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $grossMarginManagerMock
            ->expects($this->exactly(2))
            ->method($this->anything());

        /* @var GrossMarginSalesReportManager|\PHPUnit_Framework_MockObject_MockObject $grossMarginManagerMock */
        $grossMarginSalesReportManagerMock = $this
            ->getMockBuilder('Lighthouse\\ReportsBundle\\Reports\\GrossMarginSales\\GrossMarginSalesReportManager')
            ->disableOriginalConstructor()
            ->getMock();

        $grossMarginSalesReportManagerMock
            ->expects($this->exactly(2))
            ->method($this->anything());

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

        $command = new RecalculateReportsCommand(
            $grossSalesManagerMock,
            $grossMarginManagerMock,
            $grossMarginSalesReportManagerMock,
            $projectContextMock
        );

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate reports started', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports for project id1', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports for project id', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports finished', $commandTester->getDisplay());
    }
}
