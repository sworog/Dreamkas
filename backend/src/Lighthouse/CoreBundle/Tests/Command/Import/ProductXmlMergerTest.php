<?php

namespace Lighthouse\CoreBundle\Tests\Command\Import;

use Lighthouse\CoreBundle\Command\Import\ProductsXmlMerger;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Tester\CommandTester;
use FilesystemIterator;
use SplFileInfo;

class ProductXmlMergerTest extends ContainerAwareTestCase
{
    protected function prepareDirectory()
    {
        $dir = $this->getFixtureFilePath('Integration/Set10/Import/Products/XmlMerge');
        $tmpDir = '/tmp/' . uniqid('lighthouse-xml-merge-dir-', true) . '/';
        mkdir($tmpDir);
        $dirIterator = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
        /* @var SplFileInfo $file */
        foreach ($dirIterator as $file) {
            $filename = $file->getFilename();
            $destination = $tmpDir . '/' . $filename;
            copy($file->getPathname(), $destination);
            // update files mtime
            preg_match('/(\d+)-(\d+)-(\d+)_(\d+)-(\d+)-(\d+)/', $filename, $matches);
            $fileTime = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
            touch($destination, $fileTime);
        }
        return $tmpDir;
    }

    public function testExecute()
    {
        $dir = $this->prepareDirectory();
        $file = tempnam('/tmp', 'lighthouse-xml-merge-');

        $command = new ProductsXmlMerger();

        $helperSet = new HelperSet();
        $helperSet->set(new TableHelper());
        $command->setHelperSet($helperSet);

        $commandTester = new CommandTester($command);
        $input = array(
            'dir' => $dir,
            'save' => $file,
        );
        $exitCode = $commandTester->execute($input);
        $this->assertEquals(0, $exitCode);

        $display = $commandTester->getDisplay();
        Assert::assertStringOccursBefore(
            'Processing goods-catalog_2013-11-13_22-39-03.xml',
            'Processing goods-catalog_2013-11-13_22-39-02.xml',
            $display
        );
        Assert::assertStringOccursBefore(
            'Processing goods-catalog_2013-11-13_22-39-02.xml',
            'Processing goods-catalog_2013-11-13_22-39-01.xml',
            $display
        );
        Assert::assertStringOccursBefore(
            'Processing goods-catalog_2013-11-13_22-39-01.xml',
            'Processing goods-catalog_2013-11-12_10-17-58.xml',
            $display
        );

        $this->assertContains('.E', $display);
        $this->assertContains('Extra content at the end of the document at 1:2285', $display);

        $this->assertXmlFileEqualsXmlFile(
            $this->getFixtureFilePath('Integration/Set10/Import/Products/xml-merge-result.xml'),
            $file
        );
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExecuteInvalidFolderIsFile()
    {
        $command = new ProductsXmlMerger();
        $commandTester = new CommandTester($command);
        $input = array(
            'dir' => $this->getFixtureFilePath('Integration/Set10/Import/Products/goods.xml'),
            'save' => tempnam('/tmp', 'file'),
        );
        $exitCode = $commandTester->execute($input);
        $this->assertEquals(0, $exitCode);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExecuteFolderNotExists()
    {
        $command = new ProductsXmlMerger();
        $commandTester = new CommandTester($command);
        $input = array(
            'dir' => 'unknown',
            'save' => tempnam('/tmp', 'file'),
        );
        $exitCode = $commandTester->execute($input);
        $this->assertEquals(0, $exitCode);
    }
}
