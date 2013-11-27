<?php

namespace Lighthouse\CoreBundle\Util\File;

use Lighthouse\CoreBundle\Util\Iterator\ArrayIterator;
use Traversable;
use SplFileInfo;
use IteratorAggregate;
use FilesystemIterator;
use ArrayAccess;
use Closure;
use Countable;
use BadMethodCallException;

class SortableDirectoryIterator implements IteratorAggregate, ArrayAccess, Countable
{
    const SORT_ASC = true;
    const SORT_DESC = false;
    const DEFAULT_SORT = self::SORT_ASC;

    /**
     * @var FilesystemIterator
     */
    protected $filesystemIterator;

    /**
     * @var ArrayIterator|SplFileInfo[]
     */
    protected $files;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->filesystemIterator = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
    }

    /**
     * @return ArrayIterator
     */
    protected function initialize()
    {
        if (null === $this->files) {
            $this->files = new ArrayIterator();
            foreach ($this->filesystemIterator as $file) {
                $this->files->append($file);
            }
        }
        return $this->files;
    }

    /**
     * @return FilesystemIterator|SplFileInfo[]
     */
    public function getFilesystemIterator()
    {
        return $this->filesystemIterator;
    }

    /**
     * @return Traversable|ArrayIterator|SplFileInfo[]
     */
    public function getIterator()
    {
        $this->initialize();
        return $this->files;
    }

    /**
     * @param bool $direction
     * @return $this
     */
    public function sortByTime($direction = self::DEFAULT_SORT)
    {
        return $this->sort(
            function (SplFileInfo $a, SplFileInfo $b) {
                $diff = $a->getMTime() - $b->getMTime();
                return $diff;
            },
            $direction
        );
    }

    /**
     * @param bool $direction
     * @return $this
     */
    public function sortByFilename($direction = self::DEFAULT_SORT)
    {
        return $this->sort(
            function (SplFileInfo $a, SplFileInfo $b) {
                return strcmp($a->getFilename(), $b->getFilename());
            },
            $direction
        );
    }

    /**
     * @param callable $closure
     * @param bool $direction
     * @return $this
     */
    protected function sort(Closure $closure, $direction)
    {
        $this->initialize();
        $multiplier = $this->getSortMultiplier($direction);
        $this->files->usort(
            function (SplFileInfo $a, SplFileInfo $b) use ($closure, $multiplier) {
                return $multiplier * $closure($a, $b);
            }
        );
        return $this;
    }

    /**
     * @param bool $direction
     * @return int
     */
    protected function getSortMultiplier($direction)
    {
        return self::SORT_ASC == $direction ? 1 : -1;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $this->initialize();
        return $this->files->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return SplFileInfo
     */
    public function offsetGet($offset)
    {
        $this->initialize();
        return $this->files->offsetGet($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \BadMethodCallException
     */
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException('SortableDirectoryIterator is read-only.');
    }

    /**
     * @param mixed $offset
     * @throws \BadMethodCallException
     */
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('SortableDirectoryIterator is read-only.');
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->initialize();
        return $this->files->count();
    }
}
