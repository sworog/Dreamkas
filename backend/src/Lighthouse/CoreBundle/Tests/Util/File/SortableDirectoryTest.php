<?php

namespace Lighthouse\CoreBundle\Tests\Util\File;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Util\File\SortableDirectoryIterator;

class SortableDirectoryTest extends ContainerAwareTestCase
{
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidDirectory()
    {
        new SortableDirectoryIterator('unknown');
    }

    public function testSortByTimeDefault()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByTime();

        $this->assertEquals($files[0]['filename'], $dir[0]->getFilename());
        $this->assertEquals($files[1]['filename'], $dir[1]->getFilename());
        $this->assertEquals($files[2]['filename'], $dir[2]->getFilename());
        $this->assertEquals($files[3]['filename'], $dir[3]->getFilename());
        $this->assertEquals($files[4]['filename'], $dir[4]->getFilename());
    }

    public function testSortByTimeAsc()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByTime(SortableDirectoryIterator::SORT_ASC);

        $this->assertEquals($files[0]['filename'], $dir[0]->getFilename());
        $this->assertEquals($files[1]['filename'], $dir[1]->getFilename());
        $this->assertEquals($files[2]['filename'], $dir[2]->getFilename());
        $this->assertEquals($files[3]['filename'], $dir[3]->getFilename());
        $this->assertEquals($files[4]['filename'], $dir[4]->getFilename());
    }

    public function testSortByTimeDesc()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByTime(SortableDirectoryIterator::SORT_DESC);

        $this->assertEquals($files[4]['filename'], $dir[0]->getFilename());
        $this->assertEquals($files[3]['filename'], $dir[1]->getFilename());
        $this->assertEquals($files[2]['filename'], $dir[2]->getFilename());
        $this->assertEquals($files[1]['filename'], $dir[3]->getFilename());
        $this->assertEquals($files[0]['filename'], $dir[4]->getFilename());
    }

    public function testSortByFilenameDefault()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename(SortableDirectoryIterator::SORT_ASC);

        $this->assertEquals($files[0]['filename'], $dir[0]->getFilename());
        $this->assertEquals($files[1]['filename'], $dir[1]->getFilename());
        $this->assertEquals($files[2]['filename'], $dir[2]->getFilename());
        $this->assertEquals($files[3]['filename'], $dir[3]->getFilename());
        $this->assertEquals($files[4]['filename'], $dir[4]->getFilename());
    }

    public function testSortByFilenameAsc()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename(SortableDirectoryIterator::SORT_ASC);

        $this->assertEquals($files[0]['filename'], $dir[0]->getFilename());
        $this->assertEquals($files[1]['filename'], $dir[1]->getFilename());
        $this->assertEquals($files[2]['filename'], $dir[2]->getFilename());
        $this->assertEquals($files[3]['filename'], $dir[3]->getFilename());
        $this->assertEquals($files[4]['filename'], $dir[4]->getFilename());
    }

    public function testSortByFilenameDesc()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename(SortableDirectoryIterator::SORT_DESC);

        $this->assertEquals($files[4]['filename'], $dir[0]->getFilename());
        $this->assertEquals($files[3]['filename'], $dir[1]->getFilename());
        $this->assertEquals($files[2]['filename'], $dir[2]->getFilename());
        $this->assertEquals($files[1]['filename'], $dir[3]->getFilename());
        $this->assertEquals($files[0]['filename'], $dir[4]->getFilename());
    }

    public function testSortByDateFilename()
    {
        $path = $this->getFixtureFilePath('Integration/Set10/Import/Sales/Sort');
        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($path);
        $dir->sortByDateFilename(SortableDirectoryIterator::SORT_DESC);

        $this->assertCount(5, $dir);
        $this->assertEquals('purchases-99-99-9999_99-99-99.xml', $dir[0]->getFilename());
        $this->assertEquals('purchases-13-09-2013_15-09-26.xml', $dir[1]->getFilename());
        $this->assertEquals('purchases-13-09-2013_15-09-26-double.xml', $dir[2]->getFilename());

        $dir->sortByDateFilename(SortableDirectoryIterator::SORT_ASC);

        $this->assertEquals('purchases-13-09-2013_15-09-26-double.xml', $dir[2]->getFilename());
        $this->assertEquals('purchases-13-09-2013_15-09-26.xml', $dir[3]->getFilename());
        $this->assertEquals('purchases-99-99-9999_99-99-99.xml', $dir[4]->getFilename());
    }

    public function testFilterPurchaseFiles()
    {
        $path = $this->getFixtureFilePath('Integration/Set10/Import/Sales/Sort');
        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($path);

        $this->assertCount(5, $dir);
        $this->assertArrayHasKey(3, $dir);
        $this->assertArrayHasKey(4, $dir);

        $dir->filterPurchaseFiles();

        $this->assertCount(3, $dir);
        $this->assertArrayNotHasKey(3, $dir);
        $this->assertArrayNotHasKey(4, $dir);
    }

    public function testGetFileInfo()
    {
        $tmpDir = $this->createDirectory();
        $this->createFiles($tmpDir);

        $dir = new SortableDirectoryIterator($tmpDir);

        $fileInfo = $dir->getFileInfo();

        $this->assertEquals($tmpDir, $fileInfo->getPathname());
        $this->assertTrue($fileInfo->isDir());
    }

    public function testCount()
    {
        $tmpDir = $this->createDirectory();
        $this->createFiles($tmpDir);

        $dir = new SortableDirectoryIterator($tmpDir);
        $this->assertCount(5, $dir);
    }

    public function testGetIterator()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename();

        foreach ($dir as $i => $file) {
            $this->assertEquals($files[$i]['filename'], $file->getFilename());
        }
    }

    public function testOffsetExists()
    {
        $tmpDir = $this->createDirectory();
        $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename();

        $this->assertTrue(isset($dir[0]));
        $this->assertFalse(isset($dir[5]));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testOffsetUnset()
    {
        $tmpDir = $this->createDirectory();
        $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename();

        unset($dir[0]);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testOffsetSet()
    {
        $tmpDir = $this->createDirectory();
        $this->createFiles($tmpDir);

        /* @var SortableDirectoryIterator|\SplFileInfo[] $dir */
        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename();

        $value = new \SplFileInfo(__FILE__);
        $dir[0] = $value;
    }

    public function testCreateByFile()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

        $fileData = $files[0];
        /* @var \SplFileInfo[]|SortableDirectoryIterator $file */
        $file = new SortableDirectoryIterator($fileData['file']);

        $this->assertTrue($file->getFileInfo()->isFile());
        $this->assertCount(1, $file);
        $this->assertEquals($fileData['filename'], $file[0]->getFilename());
        $this->assertEquals($fileData['file'], (string) $file[0]);
        $this->assertEquals($fileData['file'], (string) $file);
    }

    public function testCreateByDir()
    {
        $tmpDir = $this->createDirectory();
        $this->createFiles($tmpDir);

        $dir = new SortableDirectoryIterator($tmpDir);

        $this->assertTrue($dir->getFileInfo()->isDir());
        $this->assertCount(5, $dir);
        $this->assertEquals($tmpDir, (string) $dir);
    }

    /**
     * @param string $tmpDir
     * @return array
     */
    protected function createFiles($tmpDir)
    {
        $files = array();
        for ($i = 0; $i < 5; $i++) {
            $file = tempnam($tmpDir, $i . '-');
            $time = mktime($i, 12, 21, 2, 6, 2012);
            touch($file, $time);
            $files[$i] = array(
                'file' => $file,
                'filename' => basename($file),
                'time' => $time,
            );
        }
        return $files;
    }

    /**
     * @return string
     */
    protected function createDirectory()
    {
        $tmpDir = sys_get_temp_dir() .'/' . uniqid('lighthouse-sortable-directory-', true);
        mkdir($tmpDir);
        return $tmpDir;
    }
}
