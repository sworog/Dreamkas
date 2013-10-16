<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Meta\MetaGeneratorInterface;

class AbstractCollection extends ArrayCollection
{
    /**
     * @var MetaGeneratorInterface[]
     */
    protected $metaGenerators = array();

    /**
     * @param array|Cursor $elements
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

    public function addMetaGenerator(MetaGeneratorInterface $metaGenerator)
    {
        $this->metaGenerators[] = $metaGenerator;
    }

    protected function makeElementWithMeta($element)
    {
        if (count($this->metaGenerators)) {
            if ($element instanceof AbstractDocument) {
                foreach ($this->metaGenerators as $metaGenerator) {
                    $element->addMeta($metaGenerator->getMetaForElement($element));
                }
            }
        }

        return $element;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        if (count($this->metaGenerators)) {
            return array_map(
                array($this, 'makeElementWithMeta'),
                parent::toArray()
            );
        } else {
            return parent::toArray();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->makeElementWithMeta(parent::get($key));
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        return $this->makeElementWithMeta(parent::first());
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        return $this->makeElementWithMeta(parent::last());
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return $this->makeElementWithMeta(parent::next());
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->makeElementWithMeta(parent::current());
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        return $this->makeElementWithMeta(parent::remove($key));
    }
}
