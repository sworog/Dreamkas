<?php

namespace LightHouse\CoreBundle\Entity;

class Product
{
    /**
     * @var array
     */
    protected $properties = array(
        'id' => null,
        'name' => null,
        'units' => null,
        'vat' => null,
        'purchasePrice' => null,
        'barcode' => null,
        'sku' => null,
        'vendorCountry' => null,
        'vendor' => null,
        'info' => null,
    );

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
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
        if (array_key_exists($name, $this->properties)) {
            $this->properties[$name] = $value;
            return;
        }
        throw new \Exception("Property '$name' does not exist");
    }
}
