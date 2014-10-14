<?php

namespace Lighthouse\ReportsBundle\Tests\Command;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Security\Project\ProjectContext;
use Lighthouse\ReportsBundle\Command\RecalculateReportsCommand;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class RecalculateReportsCommandTest extends TestCase
{
    public function testExecute()
    {
        /* @var GrossSalesReportManager|MockObject $grossSalesManagerMock */
        $grossSalesManagerMock = $this
            ->getMockBuilder('Lighthouse\\ReportsBundle\\Reports\\GrossSales\\GrossSalesReportManager')
            ->disableOriginalConstructor()
            ->getMock();

        $grossSalesManagerMock
            ->expects($this->never())
            ->method('recalculateStoreGrossSalesReport');
        $grossSalesManagerMock
            ->expects($this->never())
            ->method('recalculateGrossSalesProductReport');

        /* @var GrossMarginManager|MockObject $grossMarginManagerMock */
        $grossMarginManagerMock = $this
            ->getMockBuilder('Lighthouse\\ReportsBundle\\Reports\\GrossMargin\\GrossMarginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $grossMarginManagerMock
            ->expects($this->exactly(2))
            ->method($this->anything());

        /* @var GrossMarginSalesReportManager|MockObject $grossMarginSalesReportManagerMock */
        $grossMarginSalesReportManagerMock = $this
            ->getMockBuilder('Lighthouse\\ReportsBundle\\Reports\\GrossMarginSales\\GrossMarginSalesReportManager')
            ->disableOriginalConstructor()
            ->getMock();

        $grossMarginSalesReportManagerMock
            ->expects($this->exactly(6))
            ->method($this->anything());

        /* @var ProjectContext|MockObject $projectContextMock */
        $projectContextMock = $this
            ->getMockBuilder(ProjectContext::getClassName())
            ->disableOriginalConstructor()
            ->getMock();

        $projectContextMock
            ->expects($this->exactly(2))
            ->method('authenticate');

        $projectContextMock
            ->expects($this->exactly(2))
            ->method('logout');

        $project1Mock = new Project();
        $project1Mock->id = 'id1';
        $project2Mock = new Project();
        $project2Mock->id = 'id2';
        $projectContextMock
            ->expects($this->once())
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
