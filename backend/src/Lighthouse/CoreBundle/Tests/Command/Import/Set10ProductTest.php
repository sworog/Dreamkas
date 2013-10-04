<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Set10ProductTest extends ContainerAwareTestCase
{
    public function testExecute()
    {
        $this->clearMongoDb();

        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_product');
        $commandTester = new CommandTester($command);

        $input = array(
            'file' => __DIR__ . "/../../Fixtures/Integration/Set10/Import/goods.xml",
            'batch-size' => 4
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);
        $this->assertContains('....', $display);
        $this->assertContains("Flushing", $display);
        $this->assertContains("Done", $display);


        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);

        $this->assertContains('EEEE', $display);
        $this->assertContains('sku: Такой артикул уже есть', $display);

        $this->assertNotContains('....', $display);

        $this->assertContains("Done", $display);
    }
}
