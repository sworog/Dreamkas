<?php

namespace Lighthouse\CoreBundle\Request\ParamConverter\Links;

use Lighthouse\CoreBundle\Document\ClassNameable;
use Traversable;
use IteratorAggregate;
use ArrayIterator;

class Links implements IteratorAggregate, ClassNameable
{
    /**
     * @var Link[]|ArrayIterator
     */
    protected $iterator;

    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }

    public function __construct()
    {
        $this->iterator = new ArrayIterator();
    }

    /**
     * @param Link $link
     */
    public function add(Link $link)
    {
        $this->iterator->append($link);
    }

    /**
     * @return ArrayIterator|Link[]|Traversable
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
