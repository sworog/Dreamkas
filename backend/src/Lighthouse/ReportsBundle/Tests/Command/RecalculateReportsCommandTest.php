<?php

namespace Lighthouse\ReportsBundle\Tests\Command;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Security\Project\ProjectContext;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\ReportsBundle\Command\RecalculateReportsCommand;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportManager;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class RecalculateReportsCommandTest extends ContainerAwareTestCase
{
    public function testExecute()
    {
        $this->clearMongoDb();

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

        // project1 was created when called factory for the first time
        $this->factory()->user()->getProject('project2');

        $projectContext = $this->getContainer()->get('project.context');

        $command = new RecalculateReportsCommand(
            $grossSalesManagerMock,
            $grossMarginManagerMock,
            $grossMarginSalesReportManagerMock,
            $projectContext
        );

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate reports started', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports for project project1', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports for project project2', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports finished', $commandTester->getDisplay());
    }
}
