<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\Set10SalesImport;
use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Integration\Set10\Import\Set10Import;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Set10SalesImportTest extends WebTestCase
{
    /**
     * @var string
     */
    protected $prefix = 'lighthouse_sales_';

    protected function tearDown()
    {
        $this->clearTempFiles();
        parent::tearDown();
    }

    protected function clearTempFiles()
    {
        $tmp = new \DirectoryIterator('/tmp/');
        /* @var \DirectoryIterator $dir */
        foreach ($tmp as $dir) {
            if ($dir->isDir() && 0 === strpos($dir->getFilename(), $this->prefix)) {
                $it = new \RecursiveDirectoryIterator($dir->getPathname());
                /* @var \SplFileInfo $file */
                foreach (new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST) as $file) {
                    if ($file->isFile()) {
                        unlink($file->getPathname());
                    }
                }
                rmdir($dir->getPathname());
            }
        }
    }

    public function testExecute()
    {
        $this->createStore('197');
        $this->createStore('666');
        $this->createStore('777');
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

        $tmpDir = $this->createTempDir();
        $file1 = $this->copyFixtureFileToDir(
            'Integration/Set10/ImportSales/purchases-14-05-2012_9-18-29.xml',
            $tmpDir
        );
        $file2 = $this->copyFixtureFileToDir(
            'Integration/Set10/ImportSales/purchases-13-09-2013_15-09-26.xml',
            $tmpDir
        );

        $this->createConfig(Set10Import::URL_CONFIG_NAME, 'file://' . $tmpDir);

        $command = $this->getContainer()->get('lighthouse.core.command.integration.sales_import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        $display = $commandTester->getDisplay();

        $this->assertContains(basename($file1), $display);
        $this->assertContains("...\n", $display);
        $this->assertContains(basename($file2), $display);
        $this->assertContains(".V............SSS...\n", $display);

        $this->assertFileNotExists($file1);
        $this->assertFileNotExists($file2);

        /* @var ConfigRepository $configRepository */
        $configRepository = $this->getContainer()->get('lighthouse.core.document.repository.log');
        $cursor = $configRepository->findAll();
        $this->assertCount(1, $cursor);
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Failed to read directory
     * @dataProvider invalidDirectoryProvider
     */
    public function testInvalidDirectory($url)
    {
        $this->createConfig(Set10Import::URL_CONFIG_NAME, $url);

        $command = $this->getContainer()->get('lighthouse.core.command.integration.sales_import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());
    }

    /**
     * @return array
     */
    public function invalidDirectoryProvider()
    {
        return array(
            array('file://invalid/path'),
            //array('smb://invalid.host/invalid/path'),
        );
    }

    /**
     * @expectedException \Lighthouse\CoreBundle\Exception\RuntimeException
     * @expectedExceptionMessage Failed to read directory
     */
    public function testInvalidDirectoryIsFile()
    {
        $tmpDir = $this->createTempDir();
        $file1 = $this->copyFixtureFileToDir(
            'Integration/Set10/ImportSales/purchases-14-05-2012_9-18-29.xml',
            $tmpDir
        );

        $this->createConfig(Set10Import::URL_CONFIG_NAME, 'file://' . $file1);

        $command = $this->getContainer()->get('lighthouse.core.command.integration.sales_import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());
    }

    public function testImportInvalidXmlFile()
    {
        $tmpDir = $this->createTempDir();
        $file1 = $this->copyFixtureFileToDir(
            'Integration/Set10/ImportSales/purchases-invalid.xml',
            $tmpDir
        );

        $this->createConfig(Set10Import::URL_CONFIG_NAME, 'file://' . $tmpDir);

        /* @var Set10SalesImport $command */
        $command = $this->getContainer()->get('lighthouse.core.command.integration.sales_import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        $display = $commandTester->getDisplay();
        $this->assertContains('Failed to import sales', $display);
        $this->assertContains('Deleting "purchases-', $display);

        $this->assertFileNotExists($file1);
    }

    public function testOnlyPurchaseFilesImported()
    {
        $this->createStore('197');
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
            )
        );

        $tmpDir = $this->createTempDir();
        $file1 = $this->copyFixtureFileToDir(
            'Integration/Set10/ImportSales/purchases-invalid.xml',
            $tmpDir,
            'checks-'
        );
        $file2 = $this->copyFixtureFileToDir(
            'Integration/Set10/ImportSales/purchases-14-05-2012_9-18-29.xml',
            $tmpDir
        );
        $file3 = $this->copyFixtureFileToDir(
            'Integration/Set10/ImportSales/purchases-13-09-2013_15-09-26.xml',
            $tmpDir,
            'purchases-',
            'ico'
        );

        $this->createConfig(Set10Import::URL_CONFIG_NAME, 'file://' . $tmpDir);

        /* @var Set10SalesImport $command */
        $command = $this->getContainer()->get('lighthouse.core.command.integration.sales_import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        $display = $commandTester->getDisplay();
        $this->assertNotContains(sprintf('Importing "%s"', basename($file1)), $display);
        $this->assertNotContains(sprintf('Importing "%s"', basename($file3)), $display);
        $this->assertContains(sprintf('Importing "%s"', basename($file2)), $display);
        $this->assertContains(sprintf('Deleting "%s"', basename($file2)), $display);

        $this->assertFileExists($file1);
        $this->assertFileNotExists($file2);
        $this->assertFileExists($file3);
    }

    /**
     * @return string
     */
    protected function createTempDir()
    {
        $tmpDir = '/tmp/' . uniqid($this->prefix, true) . '/';
        mkdir($tmpDir);
        clearstatcache();
        return $tmpDir;
    }

    /**
     * @param string $file
     * @param string $dir
     * @param string $prefix
     * @param string $extension
     * @return string
     */
    protected function copyFixtureFileToDir($file, $dir, $prefix = 'purchases-', $extension = 'xml')
    {
        $source = $this->getFixtureFilePath($file);
        $destination = $dir . '/' . uniqid($prefix) . '.' . $extension;
        copy($source, $destination);
        return $destination;
    }
}
