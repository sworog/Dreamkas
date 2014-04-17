<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Lighthouse\CoreBundle\Request\ParamConverter\Filter\FilterInterface;

class ProductFilter implements  FilterInterface
{
    /**
     * @var string
     */
    protected $query;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array|string $properties
     */
    public function setProperties($properties)
    {
        $this->properties = array();
        foreach ((array) $properties as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * @param string $property
     */
    public function addProperty($property)
    {
        if (in_array($property, array('barcode', 'sku', 'name'))) {
            $this->properties[] = $property;
        }
    }

    /**
     * @return bool
     */
    public function hasProperties()
    {
        return null !== $this->properties;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return bool
     */
    public function hasQuery()
    {
        return null !== $this->query;
    }

    /**
     * @return array
     */
    public function getPropertiesWithQuery()
    {
        $result = array();
        if ($this->hasProperties() && $this->hasQuery()) {
            foreach ($this->properties as $property) {
                $result[$property] = $this->query;
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->hasQuery() && !$this->hasProperties();
    }

    /**
     * @param array $data
     * @return void
     */
    public function populate(array $data)
    {
        if (isset($data['query'])) {
            $this->setQuery($data['query']);
        }
        if (isset($data['properties'])) {
            $this->setProperties($data['properties']);
        }
    }
}
