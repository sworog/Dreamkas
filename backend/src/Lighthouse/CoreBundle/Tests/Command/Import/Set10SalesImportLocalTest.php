<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Document\Log\LogRepository;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class Set10SalesImportLocalTest extends WebTestCase
{
    /**
     * @param array|string $input
     * @param string $project
     * @return ApplicationTester
     */
    protected function execute($input, $project = null)
    {
        $arrayInput = array(
            'command' => 'lighthouse:import:sales:local',
        );

        if (is_string($input)) {
            $arrayInput['file'] = $this->getFixtureFilePath('Integration/Set10/Import/Sales/' . $input);
        } elseif (is_array($input)) {
            $arrayInput = $arrayInput + $input;
        }
        if ($project) {
            $arrayInput['--project'] = $project;
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
        $this->factory()->store()->getStoreId('197');
        $this->factory()->store()->getStoreId('666');
        $this->factory()->store()->getStoreId('777');
        $this->createProductsByNames(
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

        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute($file, $project->getName());
        $display = $commandTester->getDisplay();

        $this->assertContains($file, $display);
        $this->assertContains($expectedDisplay, $display);

        /* @var LogRepository $logRepository */
        $logRepository = $this->getContainer()->get('lighthouse.core.document.repository.log');
        $cursor = $logRepository->findAll();
        $this->assertCount($expectedLogEntriesCount, $cursor);
    }

    public function testExecuteWithErrors()
    {
        $this->factory()->store()->getStoreId('197');
        $this->createProductsByNames(
            array(
                '10001',
                '10002',
                '10003',
                '10004',
                '10005',
                '10006',
                '10007',
                '10008',
                '10009',
            )
        );
        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute('purchases-not-found.xml', $project->getName());

        $display = $commandTester->getDisplay();

        $this->assertContains("..E...............E.....                             24\nFlushing", $display);
        $this->assertContains('Product with sku "2873168" not found', $display);

        /* @var LogRepository $logRepository */
        $logRepository = $this->getContainer()->get('lighthouse.core.document.repository.log');
        $cursor = $logRepository->findAll();
        $this->assertCount(2, $cursor);
    }

    public function testExecuteWithAllErrors()
    {
        $this->factory()->store()->getStoreId('197');
        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute('purchases-14-05-2012_9-18-29.xml', $project->getName());

        $display = $commandTester->getDisplay();

        $this->assertContains(".E.E.E.E.E.E.E.E.E.E.E.E.E.E.E.E.E.E.E.E             40\nFlushing", $display);
        $this->assertContains('| Persist |', $display);

        /* @var LogRepository $logRepository */
        $logRepository = $this->getContainer()->get('lighthouse.core.document.repository.log');
        $cursor = $logRepository->findAll();
        $this->assertCount(20, $cursor);
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
        $project = $this->factory()->user()->getProject();

        $file = 'purchases-invalid.xml';

        $commandTester = $this->execute($file, $project->getName());

        $display = $commandTester->getDisplay();
        $this->assertContains($file, $display);
        $this->assertContains('Failed to import sales', $display);
    }

    public function testFileNotExists()
    {
        $project = $this->factory()->user()->getProject();

        $tester = $this->execute('unknown.xml', $project->getName());
        $this->assertEquals(1, $tester->getStatusCode());
        $this->assertContains('[UnexpectedValueException]', $tester->getDisplay());
    }

    public function testImportDirectory()
    {
        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute('Kesko/', $project->getName());
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
        $project = $this->factory()->user()->getProject();
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/Kesko'),
                '--dry-run' => true,
                '--project' => $project->getName(),
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
        $project = $this->factory()->user()->getProject();
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/Kesko'),
                '--dry-run' => true,
                '--import-date' => '2013-12-01',
                '--receipt-date' => '2013-11-04',
                '--project' => $project->getName()
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
        $project = $this->factory()->user()->getProject();
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases-invalid.xml'),
                '--dry-run' => true,
                '--import-date' => '2013-12-01',
                '--receipt-date' => '2013-11-04',
                '--project' => $project->getName()
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
        $project = $this->factory()->user()->getProject();
        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases-empty.xml'),
                '--dry-run' => true,
                '--import-date' => '2013-12-01',
                '--receipt-date' => '2013-11-04',
                '--project' => $project->getName(),
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
        $this->factory()->store()->getStoreId('197');
        $this->createProductsByNames(
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

        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/purchases-14-05-2012_9-18-29.xml'),
                '--profile' => true,
                '--project' => $project->getName()
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $this->assertContains('Run:', $commandTester->getDisplay());
    }

    public function testSortByFileDate()
    {
        $this->factory()->store()->getStores(array('1', '2', '3', '4', '5'));
        $this->createProductsByNames(
            array(
                'ЦБ000003263',
                'ЦБ000003338',
                'ЦБ000003986',
                'ЦБ000003369',
                'ЦБ000003370',
                'ЦБ000004052',
                'ЦБ000004127'
            )
        );

        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/ADM'),
                '--sort' => 'filedate',
                '--project' => $project->getName()
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $display = $commandTester->getDisplay();
        $this->assertContains('Found 5 files', $display);
        Assert::assertStringsOrder(
            array(
                'purchases-21-06-2013_22-20-22.xml',
                'purchases-04-07-2013_19-51-54.xml',
                'purchases-30-07-2013_17-31-24.xml',
                'purchases-10-08-2013_20-41-55.xml',
                'purchases-30-08-2013_17-55-55.xml'
            ),
            $display
        );
    }

    public function testSortByFileName()
    {
        $this->factory()->store()->getStores(array('1', '2', '3', '4', '5'));
        $this->createProductsByNames(
            array(
                'ЦБ000003263',
                'ЦБ000003338',
                'ЦБ000003986',
                'ЦБ000003369',
                'ЦБ000003370',
                'ЦБ000004052',
                'ЦБ000004127'
            )
        );

        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/ADM'),
                '--sort' => 'filename',
                '--project' => $project->getName()
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $display = $commandTester->getDisplay();
        $this->assertContains('Found 5 files', $display);
        Assert::assertStringsOrder(
            array(
                'purchases-04-07-2013_19-51-54.xml',
                'purchases-10-08-2013_20-41-55.xml',
                'purchases-21-06-2013_22-20-22.xml',
                'purchases-30-07-2013_17-31-24.xml',
                'purchases-30-08-2013_17-55-55.xml',
            ),
            $display
        );
    }

    public function testOnlyPurchaseFilesAreImportedOnFileDateSort()
    {
        $this->factory()->store()->getStores(array('1', '2', '3'));
        $this->createProductsByNames(
            array(
                'АВ000000221',
                'Ц0000001366',
                'Ц0000002100',
                'Ц0000001289',
                'Ц0000000937',
                'Ц0000000133',
                'Ц0000000101',
                'МЕ000000327'
            )
        );

        $project = $this->factory()->user()->getProject();

        $commandTester = $this->execute(
            array(
                'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/PurchasesDiscounts'),
                '--sort' => 'filedate',
                '--project' => $project->getName()
            )
        );
        $this->assertEquals(0, $commandTester->getStatusCode());
        $display = $commandTester->getDisplay();
        $this->assertContains('Found 3 files', $display);
        Assert::assertStringsOrder(
            array(
                'purchases-07-01-2014_11-20-01.xml',
                'purchases-01-02-2014_10-31-19.xml',
                'purchases-02-02-2014_21-50-20.xml',
            ),
            $display
        );

        $this->assertNotContains('discounts-01-02-2014_11-11-22.xml', $display);
        $this->assertNotContains('discounts-01-02-2014_11-27-22.xml', $display);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Project with name "aaa" not found
     */
    public function testInvalidProject()
    {
        $this->factory()->user()->getProject();

        $arrayInput = array(
            'command' => 'lighthouse:import:sales:local',
            'file' => $this->getFixtureFilePath('Integration/Set10/Import/Sales/PurchasesDiscounts'),
            '--project' => 'aaa'
        );

        $application = new Application(static::$kernel);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $tester = new ApplicationTester($application);
        $tester->run($arrayInput);
    }
}
