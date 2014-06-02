<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\Set10ProductsImport;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImporter;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Set10ProductsImportTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        $this->clearMongoDb();
        $this->factory()->user()->authProject();
    }

    /**
     * @param bool $reboot
     * @return CommandTester
     */
    protected function getCommandTester($reboot = false)
    {
        if ($reboot) {
            $this->rebootKernel();
        }

        /* @var Set10ProductsImport $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_products_import');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }

    public function testExecuteWithoutErrors()
    {
        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            '--batch-size' => 4
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);
        $this->assertContains('....', $display);
        $this->assertContains("Flushing", $display);
        $this->assertContains("Done", $display);
    }

    public function testDoubleExecuteWithSameFileWithoutUpdate()
    {
        $this->markTestBroken();

        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            '--batch-size' => 4
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();
        $this->assertContains('....', $display);

        // shutdown kernel to emulate two different command invokes
        $commandTester2 = $this->getCommandTester(true);
        $exitCode = $commandTester2->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester2->getDisplay();

        $this->assertContains("Starting import", $display);

        $this->assertContains('EEEE', $display);
        $this->assertContains('sku: Такой артикул уже есть', $display);

        $this->assertNotContains('....', $display);

        $this->assertContains("Done", $display);
    }

    public function testDoubleExecuteWithSameFileWithUpdate()
    {
        $this->markTestBroken();

        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();
        $this->assertContains('....', $display);

        // shutdown kernel to emulate two different command invokes
        $commandTester2 = $this->getCommandTester(true);

        $input['--update'] = true;
        $exitCode = $commandTester2->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester2->getDisplay();

        $this->assertContains("Starting import", $display);

        $this->assertContains('UUUU', $display);

        $this->assertNotContains('sku: Такой артикул уже есть', $display);
        $this->assertNotContains('....', $display);

        $this->assertContains("Done", $display);
    }

    public function testImportFolder()
    {
        $commandTester = $this->getCommandTester();
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/GoodsFolder'),
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        Assert::assertStringOccursBefore(
            'goods-catalog_01-02-2013_0-18-28.xml',
            'goods-catalog_01-01-2013_0-01-15.xml',
            $display,
            'Last file should be imported before first'
        );
    }

    public function testExecuteWithBatchSize3()
    {
        $this->markTestBroken();

        /* @var Set10ProductsImport $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_products_import');
        $commandTester = new CommandTester($command);

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            '--batch-size' => 3
        );

        $exitCode = $commandTester->execute($input);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);
        $this->assertContains("...                                                  3\nFlushing", $display);
        $this->assertContains(".                                                    4\nFlushing", $display);
        $this->assertContains("Done", $display);
    }

    /**
     * @param $batchSize
     * @param $expectedBatchSize
     * @dataProvider argumentsProvider
     */
    public function testArguments($batchSize, $expectedBatchSize)
    {
        $file = $expectedFile = $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml');

        /* @var $importer Set10ProductImporter|\PHPUnit_Framework_MockObject_MockObject */
        $importer = $this->getMock(
            'Lighthouse\\CoreBundle\\Integration\\Set10\\Import\\Products\\Set10ProductImporter',
            array(),
            array(),
            '',
            false
        );

        $importer
            ->expects($this->once())
            ->method('import')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->equalTo($expectedBatchSize)
            );


        $command = new Set10ProductsImport($importer);

        $commandTester = new CommandTester($command);

        $input = array(
            'file' => $file,
            '--batch-size' => $batchSize
        );

        $commandTester->execute($input);
    }

    /**
     * @return array
     */
    public function argumentsProvider()
    {
        return array(
            'default batch size' => array(
                null,
                1000
            ),
            'batch size 5' => array(
                5,
                5
            ),
            'batch size string' => array(
                'aaa',
                'aaa'
            ),
        );
    }

    public function testImportInvalidXml()
    {
        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods-invalid.xml'),
        );

        $exitCode = $commandTester->execute($input);
        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Error: Failed to parse node 'good': Extra content at the end of the document", $display);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testImportNotExistingFile()
    {
        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods-invalid-not-found.xml'),
        );

        $commandTester->execute($input);
    }
}
