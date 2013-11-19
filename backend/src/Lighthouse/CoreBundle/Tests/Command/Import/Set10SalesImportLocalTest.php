<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\Set10SalesImportLocal;
use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Set10SalesImportLocalTest extends WebTestCase
{
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

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/' . $file)
        );

        /* @var Set10SalesImportLocal $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_sales_import_local');
        $commandTester = new CommandTester($command);
        $commandTester->execute($input);

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

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases-14-05-2012_9-18-29.xml')
        );

        /* @var Set10SalesImportLocal $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_sales_import_local');
        $commandTester = new CommandTester($command);
        $commandTester->execute($input);

        $display = $commandTester->getDisplay();

        $this->assertContains("E......E.........EEE\n", $display);
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
                "...\n",
                0
            ),
            array(
                'purchases-14-05-2012_9-18-29.xml',
                "....................\n",
                0
            ),
        );
    }

    public function testImportInvalidXmlFile()
    {
        $file = 'purchases-invalid.xml';

        $input = array(
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/' . $file),
        );
        /* @var Set10SalesImportLocal $command */
        $command = $this->getContainer()->get('lighthouse.core.command.import.set10_sales_import_local');
        $commandTester = new CommandTester($command);
        $commandTester->execute($input);

        $display = $commandTester->getDisplay();
        $this->assertContains($file, $display);
        $this->assertContains('Failed to import sales', $display);
    }
}
