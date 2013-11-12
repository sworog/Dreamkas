<?php

namespace Lighthouse\CoreBundle\Tests\Command\Product;

use Lighthouse\CoreBundle\Command\Product\RecalculateMetricsCommand;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RecalculateMetricsCommandTest extends TestCase
{
    public function testExecute()
    {
        $mock = $this->getMock(
            'Lighthouse\\CoreBundle\\Service\\StoreProductMetricsCalculator',
            array(),
            array(),
            '',
            false
        );
        $mock->expects($this->once())
            ->method('recalculateAveragePrice');

        $command = new RecalculateMetricsCommand($mock);

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate started', $commandTester->getDisplay());
        $this->assertContains('Recalculate finished', $commandTester->getDisplay());
    }
}
