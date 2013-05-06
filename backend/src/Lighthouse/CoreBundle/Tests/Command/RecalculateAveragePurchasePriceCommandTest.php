<?php

namespace Lighthouse\CoreBundle\Tests\Command;

use Lighthouse\CoreBundle\Command\RecalculateAveragePurchasePriceCommand;
use Symfony\Component\Console\Tester\CommandTester;

class RecalculateAveragePurchasePriceCommandTest extends \PHPUnit_Framework_TestCase
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

        $command = new RecalculateAveragePurchasePriceCommand();
        $command->setAveragePriceService($mock);

        $commandTester = new CommandTester($command);

        $input = array();

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Recalculate started', $commandTester->getDisplay());
        $this->assertContains('Recalculate finished', $commandTester->getDisplay());
    }
}

