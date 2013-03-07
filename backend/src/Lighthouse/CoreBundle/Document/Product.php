<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

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
 *
 * @MongoDB\Document
 * @Serializer\XmlRoot("product")
 */
class Product
{
    /** @MongoDB\Id */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @var string
     */
    protected $name;

    /** @MongoDB\String */
    protected $units;
    /** @MongoDB\String */
    protected $vat;
    /** @MongoDB\String */
    protected $purchasePrice;
    /** @MongoDB\String */
    protected $barcode;
    /** @MongoDB\String */
    protected $sku;
    /** @MongoDB\String */
    protected $vendorCountry;
    /** @MongoDB\String */
    protected $vendor;
    /** @MongoDB\String */
    protected $info;

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
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
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return;
        }
        throw new \Exception("Property '$name' does not exist");
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'units' => $this->units,
            'vat' => $this->vat,
            'purchasePrice' => $this->purchasePrice,
            'barcode' => $this->barcode,
            'sku' => $this->sku,
            'vendorCountry' => $this->vendorCountry,
            'vendor' => $this->vendor,
            'info' => $this->info,
        );
    }

    /**
     * @param array $data
     * @return Product $this
     */
    public function populate(array $data)
    {
        foreach ($data as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }

        return $this;
    }
}
