<?php

namespace Lighthouse\ReportsBundle\Tests\Command;

use Lighthouse\ReportsBundle\Command\RecalculateReportsCommand;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RecalculateReportsCommandTest extends TestCase
{
    public function testExecute()
    {
        /* @var GrossSalesReportManager|\PHPUnit_Framework_MockObject_MockObject $grossSalesManagerMock */
        $grossSalesManagerMock = $this->getMock(
            'Lighthouse\\ReportsBundle\\Reports\\GrossSales\\GrossSalesReportManager',
            array(),
            array(),
            '',
            false
        );
        $grossSalesManagerMock
            ->expects($this->once())
            ->method('recalculateStoreGrossSalesReport');
        $grossSalesManagerMock
            ->expects($this->once())
            ->method('recalculateGrossSalesProductReport');

        /* @var GrossMarginManager|\PHPUnit_Framework_MockObject_MockObject $grossMarginManagerMock */
        $grossMarginManagerMock = $this->getMock(
            'Lighthouse\\ReportsBundle\\Reports\\GrossMargin\\GrossMarginManager',
            array(),
            array(),
            '',
            false
        );
        $grossMarginManagerMock
            ->expects($this->exactly(3))
            ->method($this->anything());

        $command = new RecalculateReportsCommand($grossSalesManagerMock, $grossMarginManagerMock);

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate reports started', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports finished', $commandTester->getDisplay());
    }
}
