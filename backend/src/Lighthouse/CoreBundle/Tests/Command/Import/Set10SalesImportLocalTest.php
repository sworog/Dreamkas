<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\Set10SalesImportLocal;
use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Set10SalesImportLocalTest extends WebTestCase
{
    /**
     * @param array|string $input
     * @return CommandTester
     */
    protected function execute($input)
    {
        if (is_string($input)) {
            $input = array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/' . $input),
            );
        }
        /* @var Set10SalesImportLocal $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_sales_import_local');
        $commandTester = new CommandTester($command);
        $commandTester->execute($input);
        return $commandTester;
    }

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($file, $expectedDisplay, $expectedLogEntriesCount)
    {
        $this->factory->getStore('197');
        $this->factory->getStore('666');
        $this->factory->getStore('777');
        $this->createProductsBySku(
            array(
                '1',
                '3',
                '7',
                '8594403916157',
                '2873168',
                '2809727',
                '25525687',
                '55557',
                '8594403110111',
                '4601501082159',
                'Кит-Кат-343424',
            )
        );

        $commandTester = $this->execute($file);
        $display = $commandTester->getDisplay();

        $this->assertContains($file, $display);
        $this->assertContains($expectedDisplay, $display);

        /* @var ConfigRepository $configRepository */
        $configRepository = $this->getContainer()->get('lighthouse.core.document.repository.log');
        $cursor = $configRepository->findAll();
        $this->assertCount($expectedLogEntriesCount, $cursor);
    }

    public function testExecuteWithErrors()
    {
        $this->factory->getStore('197');
        $this->createProductsBySku(
            array(
                '8594403916157',
                '2873168',
                '2809727',
                '25525687',
                '55557',
                '8594403110111',
                '4601501082159',
                'Кит-Кат-343424',
            )
        );

        $commandTester = $this->execute('purchases-14-05-2012_9-18-29.xml');

        $display = $commandTester->getDisplay();

        $this->assertContains("E......E.........EEE                                      15\nFlushing", $display);
        $this->assertContains('Product with sku "1" not found', $display);
        $this->assertContains('Product with sku "7" not found', $display);
        $this->assertContains('Product with sku "3" not found', $display);

        /* @var ConfigRepository $configRepository */
        $configRepository = $this->getContainer()->get('lighthouse.core.document.repository.log');
        $cursor = $configRepository->findAll();
        $this->assertCount(5, $cursor);
    }

    /**
     * @return array
     */
    public function executeProvider()
    {
        return array(
            array(
                'purchases-13-09-2013_15-09-26.xml',
                "...                                                  3\nFlushing",
                0
            ),
            array(
                'purchases-14-05-2012_9-18-29.xml',
                "....................                                 20\nFlushing",
                0
            ),
        );
    }

    public function testImportInvalidXmlFile()
    {
        $file = 'purchases-invalid.xml';

        $commandTester = $this->execute($file);

        $display = $commandTester->getDisplay();
        $this->assertContains($file, $display);
        $this->assertContains('Failed to import sales', $display);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testFileNotExists()
    {
        $this->execute('unknown.xml');
    }

    public function testImportDirectory()
    {
        $commandTester = $this->execute('Kesko/');
        $display = $commandTester->getDisplay();
        $this->assertContains('Found 2 files', $display);
        $this->assertContains('purchases-success-2013.11.04-00.03.09.514.xml', $display);
        $this->assertContains('purchases-success-2013.11.05-19.33.50.602.xml', $display);
        Assert::assertStringOccursBefore(
            'purchases-success-2013.11.04-00.03.09.514.xml',
            'purchases-success-2013.11.05-19.33.50.602.xml',
            $display
        );
    }
}
