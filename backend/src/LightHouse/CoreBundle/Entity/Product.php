<?php

namespace LightHouse\CoreBundle\Entity;

/**
 *
 * @property string $id
 * @property string $name
 * @property string $units kg,gr,l
 * @property int    $vat in %
 * @property int    $purchasePrice
 * @property string $barcode
 * @property string $sku
 * @property string $vendorCountry
 * @property string $vendor
 * @property string $info
 */
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

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
     * @param array $data
     * @return Product $this
     */
    public function populate(array $data)
    {
        foreach ($this->properties as $key => $value) {
            if (isset($data[$key])) {
                $this->properties[$key] = $data[$key];
            }
        }

        return $this;
    }
}
