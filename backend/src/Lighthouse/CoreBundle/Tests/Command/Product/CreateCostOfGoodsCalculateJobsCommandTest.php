<?php

namespace Lighthouse\CoreBundle\Tests\Command\Product;

use Lighthouse\CoreBundle\Command\Products\CreateCostOfGoodsCalculateJobsCommand;
use Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods\CostOfGoodsManager;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateCostOfGoodsCalculateJobsCommandTest extends TestCase
{
    public function testExecute()
    {
        /* @var CostOfGoodsManager|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\TrialBalance\\CostOfGoods\\CostOfGoodsManager',
            array(),
            array(),
            '',
            false
        );
        $mock
            ->expects($this->once())
            ->method('createCalculateJobsForUnprocessed')
            ->will($this->returnValue(10));

        $command = new CreateCostOfGoodsCalculateJobsCommand($mock);

        $commandTester = new CommandTester($command);

        $exitCode = $commandTester->execute(array());

        $this->assertSame(0, $exitCode);

        $this->assertContains('Creating jobs started', $commandTester->getDisplay());
        $this->assertContains('Creating jobs finished, created 10 jobs', $commandTester->getDisplay());
    }
}
