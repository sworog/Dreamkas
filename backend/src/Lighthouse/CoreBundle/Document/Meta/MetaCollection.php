<?php

namespace Lighthouse\CoreBundle\Document\Meta;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Meta\MetaGeneratorInterface;
use ArrayIterator;

class MetaCollection extends ArrayCollection
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

    /**
     * @param MetaGeneratorInterface $metaGenerator
     */
    public function addMetaGenerator(MetaGeneratorInterface $metaGenerator)
    {
        $this->metaGenerators[] = $metaGenerator;
    }

    /**
     * @param mixed $element
     * @return MetaDocument
     */
    protected function makeMetaElement($element)
    {
        $metaElement = new MetaDocument($element);
        foreach ($this->metaGenerators as $metaGenerator) {
            $metaElement->addMeta($metaGenerator->getMetaForElement($element));
        }

        return $metaElement;
    }

    /**
     * {@inheritDoc}
     * @return MetaDocument[]
     */
    public function toArray()
    {
        return array_map(
            array($this, 'makeMetaElement'),
            parent::toArray()
        );
    }

    /**
     * {@inheritDoc}
     * @return MetaDocument
     */
    public function get($key)
    {
        return $this->makeMetaElement(parent::get($key));
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * {@inheritDoc}
     * @return MetaDocument
     */
    public function first()
    {
        return $this->makeMetaElement(parent::first());
    }

    /**
     * {@inheritDoc}
     * @return MetaDocument
     */
    public function last()
    {
        return $this->makeMetaElement(parent::last());
    }

    /**
     * {@inheritDoc}
     * @return MetaDocument
     */
    public function next()
    {
        return $this->makeMetaElement(parent::next());
    }

    /**
     * {@inheritDoc}
     * @return MetaDocument
     */
    public function current()
    {
        return $this->makeMetaElement(parent::current());
    }

    /**
     * {@inheritDoc}
     * @return MetaDocument
     */
    public function remove($key)
    {
        return $this->makeMetaElement(parent::remove($key));
    }
}
