<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\Set10ProductsImport;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImporter;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\OutputInterface;

class Set10ProductsImportTest extends ContainerAwareTestCase
{
    /**
     * @param bool $shutdown
     * @return CommandTester
     */
    protected function getCommandTester($shutdown = false)
    {
        if ($shutdown) {
            static::$kernel->shutdown();
        }

        /* @var Set10ProductsImport $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_products_import');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }

    public function testExecuteWithoutErrors()
    {
        $this->clearMongoDb();

        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            'batch-size' => 4
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
        $this->clearMongoDb();

        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            'batch-size' => 4
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
        $this->clearMongoDb();

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
        $this->clearMongoDb();

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

    public function testExecuteWithVerbose()
    {
        $this->clearMongoDb();

        /* @var Set10ProductsImport $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_products_import');
        $commandTester = new CommandTester($command);

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            'batch-size' => 3
        );
        $options = array('verbosity' => OutputInterface::VERBOSITY_VERBOSE);

        $exitCode = $commandTester->execute($input, $options);

        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);
        $this->assertNotContains('....', $display);
        $this->assertContains("Persist product", $display);
        $this->assertContains("Item time", $display);
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
            'batch-size' => $batchSize
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
        $this->clearMongoDb();

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
        $this->clearMongoDb();

        $commandTester = $this->getCommandTester();

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods-invalid-not-found.xml'),
        );

        $commandTester->execute($input);
    }
}
