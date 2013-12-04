<?php

namespace Lighthouse\CoreBundle\Tests\Command\Report;

use Lighthouse\CoreBundle\Command\Reports\RecalculateReportsCommand;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesReportManager;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RecalculateReportsCommandTest extends TestCase
{
    public function testExecute()
    {
        /* @var GrossSalesReportManager|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\Report\\GrossSales\\GrossSalesReportManager',
            array(),
            array(),
            '',
            false
        );
        $mock
            ->expects($this->once())
            ->method('recalculateStoreGrossSalesReport');

        $command = new RecalculateReportsCommand($mock);

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate reports started', $commandTester->getDisplay());
        $this->assertContains('Recalculate reports finished', $commandTester->getDisplay());
    }
}
