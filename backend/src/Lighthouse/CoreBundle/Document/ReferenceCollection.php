<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ReferenceCollection extends ArrayCollection
{
    /**
     * @var object|array
     */
    protected $parent;

    /**
     * @var string
     */
    protected $propertyPath;

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @param object|array $parent
     * @param string $propertyPath
     * @param array $elements
     */
    public function __construct($parent, $propertyPath, array $elements = array())
    {
        $this->parent = $parent;
        $this->propertyPath = $propertyPath;

        parent::__construct($elements);
    }

    /**
     * @return PropertyAccessor
     */
    protected function getAccessor()
    {
        if (null === $this->accessor) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }
        return $this->accessor;
    }

    /**
     * @param object|array $value
     */
    protected function setParent($value)
    {
        if (null !== $value) {
            $this->getAccessor()->setValue($value, $this->propertyPath, $this->parent);
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function add($value)
    {
        $this->setParent($value);
        return parent::add($value);
    }

    /**
     * @param int|string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->setParent($value);
        parent::set($key, $value);
    }
}
