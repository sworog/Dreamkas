<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;

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
    public $id;
    /** @MongoDB\String */
    public $name;
    /** @MongoDB\String */
    public $units;
    /** @MongoDB\String */
    public $vat;
    /** @MongoDB\String */
    public $purchasePrice;
    /** @MongoDB\String */
    public $barcode;
    /** @MongoDB\String */
    public $sku;
    /** @MongoDB\String */
    public $vendorCountry;
    /** @MongoDB\String */
    public $vendor;
    /** @MongoDB\String */
    public $info;

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

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set units
     *
     * @param string $units
     * @return \Product
     */
    public function setUnits($units)
    {
        $this->units = $units;
        return $this;
    }

    /**
     * Get units
     *
     * @return string $units
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set vat
     *
     * @param string $vat
     * @return \Product
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * Get vat
     *
     * @return string $vat
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set purchasePrice
     *
     * @param string $purchasePrice
     * @return \Product
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
        return $this;
    }

    /**
     * Get purchasePrice
     *
     * @return string $purchasePrice
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * Set barcode
     *
     * @param string $barcode
     * @return \Product
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * Get barcode
     *
     * @return string $barcode
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * @return \Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Get sku
     *
     * @return string $sku
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set vendorCountry
     *
     * @param string $vendorCountry
     * @return \Product
     */
    public function setVendorCountry($vendorCountry)
    {
        $this->vendorCountry = $vendorCountry;
        return $this;
    }

    /**
     * Get vendorCountry
     *
     * @return string $vendorCountry
     */
    public function getVendorCountry()
    {
        return $this->vendorCountry;
    }

    /**
     * Set vendor
     *
     * @param string $vendor
     * @return \Product
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * Get vendor
     *
     * @return string $vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return \Product
     */
    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * Get info
     *
     * @return string $info
     */
    public function getInfo()
    {
        return $this->info;
    }
}
