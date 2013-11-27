<?php

namespace Lighthouse\CoreBundle\Tests\Util\File;

use Lighthouse\CoreBundle\Util\File\SortableDirectoryIterator;

class SortableDirectoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidDirectory()
    {
        new SortableDirectoryIterator('unknown');
    }

    public function testSortByTimeDefault()
    {
        $tmpDir = $this->createDirectory();
        $files = $this->createFiles($tmpDir);

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

        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename(SortableDirectoryIterator::SORT_DESC);

        $this->assertEquals($files[4]['filename'], $dir[0]->getFilename());
        $this->assertEquals($files[3]['filename'], $dir[1]->getFilename());
        $this->assertEquals($files[2]['filename'], $dir[2]->getFilename());
        $this->assertEquals($files[1]['filename'], $dir[3]->getFilename());
        $this->assertEquals($files[0]['filename'], $dir[4]->getFilename());

        $filesystem = $dir->getFilesystemIterator();
        $filesystem->rewind();
        $this->assertNotEquals($filesystem->current()->getFilename(), $dir[0]->getFilename());
    }

    public function testGetFilesystemIterator()
    {
        $tmpDir = $this->createDirectory();
        $this->createFiles($tmpDir);

        $dir = new SortableDirectoryIterator($tmpDir);

        $filesystem = $dir->getFilesystemIterator();

        $this->assertEquals($tmpDir, $filesystem->getPath());
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

        $dir = new SortableDirectoryIterator($tmpDir);
        $dir->sortByFilename();

        $value = new \SplFileInfo(__FILE__);
        $dir[0] = $value;
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
