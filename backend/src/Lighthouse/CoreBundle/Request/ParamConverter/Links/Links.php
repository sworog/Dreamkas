<?php

namespace Lighthouse\CoreBundle\Request\ParamConverter\Links;

use Lighthouse\CoreBundle\Request\ParamConverter\Links\Link;
use Traversable;
use IteratorAggregate;
use ArrayIterator;

class Links implements IteratorAggregate
{
    /**
     * @var Link[]|ArrayIterator
     */
    protected $iterator;

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
