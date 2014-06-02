<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\Set10ProductsImport;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\Set10ProductImporter;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Set10ProductsImportTest extends ContainerAwareTestCase
{
    protected function setUp()
    {
        $this->clearMongoDb();
    }

    /**
     * @param array $input
     * @param bool $catchExceptions
     * @return ApplicationTester
     */
    protected function execute(array $input = array(), $catchExceptions = true)
    {
        return $this->createConsoleTester($catchExceptions, true)->runProjectCommand(
            'lighthouse:import:products',
            $this->factory()->user()->getProject(),
            $input
        );
    }

    public function testExecuteWithoutErrors()
    {
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            '--batch-size' => 4
        );

        $commandTester = $this->execute($input);

        $this->assertEquals(0, $commandTester->getStatusCode());

        $display = $commandTester->getDisplay();

        $this->assertContains("Starting import", $display);
        $this->assertContains('....', $display);
        $this->assertContains("Flushing", $display);
        $this->assertContains("Done", $display);
    }

    public function testDoubleExecuteWithSameFileWithoutUpdate()
    {
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            '--batch-size' => 4
        );

        $commandTester = $this->execute($input);

        $this->assertEquals(0, $commandTester->getStatusCode());

        $display = $commandTester->getDisplay();
        $this->assertContains('....', $display);

        $commandTester2 = $this->execute($input);

        $this->assertEquals(0, $commandTester2->getStatusCode());

        $display = $commandTester2->getDisplay();

        $this->assertContains("Starting import", $display);

        $this->assertContains('EEEE', $display);
        $this->assertContains('sku: Такой артикул уже есть', $display);

        $this->assertNotContains('....', $display);

        $this->assertContains("Done", $display);
    }

    public function testDoubleExecuteWithSameFileWithUpdate()
    {
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
        );

        $commandTester = $this->execute($input);

        $this->assertEquals(0, $commandTester->getStatusCode());

        $display = $commandTester->getDisplay();
        $this->assertContains('....', $display);

        $input['--update'] = true;

        $commandTester2 = $this->execute($input);

        $this->assertEquals(0, $commandTester2->getStatusCode());

        $display = $commandTester2->getDisplay();

        $this->assertContains("Starting import", $display);

        $this->assertContains('UUUU', $display);

        $this->assertNotContains('sku: Такой артикул уже есть', $display);
        $this->assertNotContains('....', $display);

        $this->assertContains("Done", $display);
    }

    public function testImportFolder()
    {
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/GoodsFolder'),
        );

        $commandTester = $this->execute($input);

        $this->assertEquals(0, $commandTester->getStatusCode());

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
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            '--batch-size' => 3
        );
        $commandTester = $this->execute($input);

        $this->assertEquals(0, $commandTester->getStatusCode());

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
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods-invalid.xml'),
        );

        $commandTester = $this->execute($input);

        $this->assertEquals(0, $commandTester->getStatusCode());

        $this->assertContains(
            "Error: Failed to parse node 'good': Extra content at the end of the document",
            $commandTester->getDisplay()
        );
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testImportNotExistingFile()
    {
        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods-invalid-not-found.xml'),
        );

        $this->execute($input, false);
    }
}
