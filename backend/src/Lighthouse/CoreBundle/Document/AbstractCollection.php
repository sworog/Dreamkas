<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\MongoDB\Cursor;

class AbstractCollection extends ArrayCollection
{
    /**
     * @param array|Cursor|Collection $elements
     */
    public function __construct($elements = array())
    {
        if ($elements instanceof Cursor) {
            $elements = $elements->toArray(false);
        } elseif ($elements instanceof Collection) {
            $elements = $elements->toArray();
        }
        parent::__construct($elements);
    }
}
