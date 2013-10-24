<?php

namespace Lighthouse\CoreBundle\Meta;

use JMS\Serializer\Annotation as Serializer;

class MetaDocument
{
    /**
     * @Serializer\SerializedName("_meta")
     * @var array
     */
    protected $meta = array();

    /**
     * @var mixed
     * @Serializer\Inline
     */
    protected $element;

    /**
     * @param $element
     */
    public function __construct($element)
    {
        $this->element = $element;
    }

    /**
     * @return mixed
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (property_exists($this->element, $name)) {
            return $this->element->$name;
        }
        throw new \Exception("Property '$name' does not exist");
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (property_exists($this->element, $name)) {
            $this->element->$name = $value;
            return;
        }
        throw new \Exception("Property '$name' does not exist");
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     */
    public function addMeta(array $meta)
    {
        $this->meta += $meta;
    }
}
