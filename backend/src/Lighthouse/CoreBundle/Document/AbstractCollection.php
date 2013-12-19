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

    /**
     * @return AbstractCollection
     */
    public function normalizeKeys()
    {
        $values = $this->getValues();
        $this->clear();
        foreach ($values as $value) {
            $this->add($value);
        }
        return $this;
    }

    /**
     * @param string $field
     * @return array
     */
    public function extractField($field)
    {
        return array_map(
            function ($value) use ($field) {
                return $value->$field;
            },
            $this->getValues()
        );
    }

    /**
     * @return array
     */
    public function getIds()
    {
        return $this->extractField('id');
    }
}
