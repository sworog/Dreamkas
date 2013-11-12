<?php

namespace Lighthouse\CoreBundle\Tests\Command;

use Lighthouse\CoreBundle\Command\Product\RecalculateMetricsCommand;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RecalculateAveragePurchasePriceCommandTest extends TestCase
{
    public function testExecute()
    {
        $mock = $this->getMock(
            'Lighthouse\\CoreBundle\\Service\\AveragePriceService',
            array(),
            array(),
            '',
            false
        );
        $mock->expects($this->once())
            ->method('recalculateAveragePrice');

        $command = new RecalculateMetricsCommand();
        $command->setMetricsCalculator($mock);

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate started', $commandTester->getDisplay());
        $this->assertContains('Recalculate finished', $commandTester->getDisplay());
    }
}
