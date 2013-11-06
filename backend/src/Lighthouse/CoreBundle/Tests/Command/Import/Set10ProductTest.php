<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\Set10Product;
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
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/goods.xml'),
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

    /**
     * @param $file
     * @param $batchSize
     * @param $expectedFile
     * @param $expectedBatchSize
     * @dataProvider argumentsProvider
     */
    public function testArguments($file, $batchSize, $expectedFile, $expectedBatchSize)
    {
        $parser = $this->getMock(
            'Lighthouse\\CoreBundle\\Integration\\Set10\\Import\\Products\\Set10ProductImportXmlParser',
            array(),
            array(),
            '',
            false
        );

        $parser
            ->expects($this->once())
            ->method('setXmlFilePath')
            ->with($this->equalTo($expectedFile));

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
                $this->equalTo($parser),
                $this->anything(),
                $this->equalTo($expectedBatchSize)
            );


        $command = new Set10Product();
        $command->setParserProvider($parser);
        $command->setImporterProvider($importer);

        $commandTester = new CommandTester($command);

        $input = array(
            'file' => $file,
            'batch-size' => $batchSize
        );

        $commandTester->execute($input);
    }

    public function argumentsProvider()
    {
        return array(
            'default batch size' => array('file', null, 'file', 1000),
            'batch size 5' => array('file', 5, 'file', 5),
            'batch size string' => array('file', 'aaa', 'file', 'aaa'),
        );
    }
}
