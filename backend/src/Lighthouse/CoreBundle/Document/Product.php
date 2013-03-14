<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

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
 * @Unique("sku")
 */
class Product
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300")
     * @var string
     *
     */
    protected $name;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     */
    protected $units;

    /**
     * @MongoDB\Int
     * @Assert\NotBlank
     * @Assert\Range(min="0")
     */
    protected $vat;

    /**
     * @MongoDB\Float
     *
     * @Assert\NotBlank
     * @LighthouseAssert\Price
     */
    protected $purchasePrice;

    /**
     * @MongoDB\String
     * @Assert\Length(max="200")
     */
    protected $barcode;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="200")
     */
    protected $sku;

    /**
     * @MongoDB\String
     * @Assert\Length(max="100")
     */
    protected $vendorCountry;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300")
     */
    protected $vendor;

    /**
     * @MongoDB\String
     * @Assert\Length(max="2000")
     */
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
