<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Cursor;

class AbstractCollection extends ArrayCollection
{
    /**
     * @param array|Cursor $elements
     */
    public function __construct($elements = array())
    {
        if ($elements instanceof Cursor) {
            $elements = $elements->toArray(false);
        }
        parent::__construct($elements);
    }
}
