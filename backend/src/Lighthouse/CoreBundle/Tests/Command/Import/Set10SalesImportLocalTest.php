<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class Set10SalesImportLocalTest extends WebTestCase
{
    /**
     * @param array|string $input
     * @return ApplicationTester
     */
    protected function execute($input)
    {
        $arrayInput = array(
            'command' => 'lighthouse:import:sales:local',
        );

        if (is_string($input)) {
            $arrayInput['file'] = $this->getFixtureFilePath('Integration/Set10/Import/Sales/' . $input);
        } elseif (is_array($input)) {
            $arrayInput = $arrayInput + $input;
        }

        $application = new Application(static::$kernel);
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);

        $tester->run($arrayInput);
        return $tester;
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

        $this->assertContains(".E...........E............E.E.E                      31\nFlushing", $display);
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
                "..........................                           26\nFlushing",
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

    public function testFileNotExists()
    {
        $tester = $this->execute('unknown.xml');
        $this->assertEquals(1, $tester->getStatusCode());
        $this->assertContains('[UnexpectedValueException]', $tester->getDisplay());
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

    public function testDryRun()
    {
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/Kesko'),
                '--dry-run' => true
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $expectedDisplay = <<<EOF
Found 2 files
Checking "purchases-success-2013.11.04-00.03.09.514.xml"
First receipt date: "2013-11-04T01:52:27+0200" will be shifted to "2013-11-04T01:52:27+0200"
Checking "purchases-success-2013.11.05-19.33.50.602.xml"
First receipt date: "2013-11-05T21:22:30+0200" will be shifted to "2013-11-05T21:22:30+0200"
EOF;
        $this->assertContains($expectedDisplay, $commandTester->getDisplay());
    }

    public function testDryRunWithStartAndEndDates()
    {
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/Kesko'),
                '--dry-run' => true,
                '--start-date' => '2013-12-01',
                '--end-date' => '2013-11-04'
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $expectedDisplay = <<<EOF
Found 2 files
Checking "purchases-success-2013.11.04-00.03.09.514.xml"
First receipt date: "2013-11-04T01:52:27+0200" will be shifted to "2013-12-01T01:52:27+0200"
Checking "purchases-success-2013.11.05-19.33.50.602.xml"
First receipt date: "2013-11-05T21:22:30+0200" will be shifted to "2013-12-02T21:22:30+0200"
EOF;
        $this->assertContains($expectedDisplay, $commandTester->getDisplay());
    }

    public function testDryRunWithStartAndEndDatesAndInvalidFile()
    {
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases-invalid.xml'),
                '--dry-run' => true,
                '--start-date' => '2013-12-01',
                '--end-date' => '2013-11-04'
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $expectedDisplay = <<<EOF
Found 1 files
Checking "purchases-invalid.xml"
Failed to parse xml: Failed to parse node 'purchase': Couldn't find end of Start Tag position
EOF;
        $this->assertContains($expectedDisplay, $commandTester->getDisplay());
    }

    public function testDryRunWithStartAndEndDatesAndEmptyFile()
    {
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases-empty.xml'),
                '--dry-run' => true,
                '--start-date' => '2013-12-01',
                '--end-date' => '2013-11-04'
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $expectedDisplay = <<<EOF
Found 1 files
Checking "purchases-empty.xml"
No receipts found
EOF;
        $this->assertContains($expectedDisplay, $commandTester->getDisplay());
    }

    public function testProfile()
    {
        $this->factory->getStore('197');
        $this->createProductsBySku(
            array(
                '1',
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

        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases-14-05-2012_9-18-29.xml'),
                '--profile' => true
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $this->assertContains('Run:', $commandTester->getDisplay());
    }
}
