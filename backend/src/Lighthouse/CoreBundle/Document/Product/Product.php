<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints as MongoDBAssert;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\Product\Barcode\Barcode;
use Lighthouse\CoreBundle\Document\Product\Type\AlcoholType;
use Lighthouse\CoreBundle\Document\Product\Type\Typeable;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType;
use Lighthouse\CoreBundle\Document\Product\Type\WeightType;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Versionable\VersionableInterface;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;

/**
 *
 * @property string $id
 * @property string $name
 * @property UnitType|WeightType|AlcoholType|Typeable $typeProperties
 * @property string $units kg,gr,l
 * @property int    $vat in %
 * @property Money  $purchasePrice
 * @property string $barcode
 * @property string $sku
 * @property string $vendorCountry
 * @property string $vendor
 * @property string $info
 * @property Money  $retailPriceMin
 * @property Money  $retailPriceMax
 * @property float  $retailMarkupMin
 * @property float  $retailMarkupMax
 * @property string $retailPricePreference
 * @property AbstractRounding $rounding
 * @property SubCategory $subCategory
 * @property Barcode[] $barcodes
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Product\ProductRepository"
 * )
 * @MongoDB\InheritanceType("COLLECTION_PER_CLASS")
 * @MongoDBAssert\Unique(fields="sku", message="lighthouse.validation.errors.product.sku.unique")
 *
 * @LighthouseAssert\Product\RetailPrice
 * @LighthouseAssert\Product\BarcodeUnique
 */
class Product extends AbstractDocument implements VersionableInterface
{
    const RETAIL_PRICE_PREFERENCE_PRICE = 'retailPrice';
    const RETAIL_PRICE_PREFERENCE_MARKUP = 'retailMarkup';

    /**
     * @Serializer\Exclude
     * @var array
     */
    public static $retailPricePreferences = array(
        self::RETAIL_PRICE_PREFERENCE_PRICE => 'lighthouse.document.product.retailPrice',
        self::RETAIL_PRICE_PREFERENCE_MARKUP => 'lighthouse.document.product.retailMarkup',
    );

    /**
     * @MongoDB\Id
     * @var string
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $name;

    /**
     * @MongoDB\EmbedOne(
     *   discriminatorField="type",
     *   discriminatorMap={
     *      "unit"="Lighthouse\CoreBundle\Document\Product\Type\UnitType",
     *      "weight"="Lighthouse\CoreBundle\Document\Product\Type\WeightType",
     *      "alcohol"="Lighthouse\CoreBundle\Document\Product\Type\AlcoholType"
     *   }
     * )
     * @var Typeable
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $typeProperties;

    /**
     * @MongoDB\Int
     * @Assert\NotBlank(message="lighthouse.validation.errors.product.vat.blank")
     * @Assert\Range(min="0")
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $vat;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $purchasePrice;

    /**
     * @MongoDB\String
     * @Assert\Length(max="200", maxMessage="lighthouse.validation.errors.length")
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $barcode;

    /**
     * @Generated(startValue=10000)
     * @MongoDB\UniqueIndex
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $sku;

    /**
     * @MongoDB\String
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $vendorCountry;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $vendor;

    /**
     * @MongoDB\String
     * @Assert\Length(max="2000", maxMessage="lighthouse.validation.errors.length")
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $info;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $retailPriceMin;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $retailPriceMax;

    /**
     * @MongoDB\Float
     * @var float
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $retailMarkupMin;

    /**
     * @MongoDB\Float
     * @var float
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $retailMarkupMax;

    /**
     * @MongoDB\String
     * @var string
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $retailPricePreference = self::RETAIL_PRICE_PREFERENCE_MARKUP;

    /**
     * @var AbstractRounding
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $rounding;

    /**
     * @Serializer\Exclude
     * @MongoDB\String
     * @var string
     */
    protected $roundingId;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory",
     *     simple=true,
     *     cascade="persist"
     * )
     * @MongoDB\Index
     * @Assert\NotBlank
     * @Serializer\Groups({"Default"})
     * @var SubCategory
     */
    protected $subCategory;

    /**
     * @MongoDB\EmbedMany(targetDocument="Lighthouse\CoreBundle\Document\Product\Barcode\Barcode")
     * @var array|Barcode[]
     */
    protected $barcodes = array();

    public function __construct()
    {
        $this->typeProperties = new UnitType();
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("units")
     * @Serializer\Groups({"Default", "Collection"})
     * @return string
     */
    public function getUnits()
    {
        return $this->typeProperties->getUnits();
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("type")
     * @Serializer\Groups({"Default", "Collection"})
     * @return string
     */
    public function getType()
    {
        return $this->typeProperties->getType();
    }

    /**
     * @param AbstractRounding $rounding
     */
    public function setRounding(AbstractRounding $rounding = null)
    {
        $this->rounding = $rounding;

        if (null !== $rounding) {
            $this->roundingId = $rounding->getName();
        } else {
            $this->roundingId = null;
        }
    }

    /**
     * @return string
     */
    public function getVersionClass()
    {
        return ProductVersion::getClassName();
    }

    /**
     * @param string $barcode
     * @return bool
     */
    public function hasProductBarcode($barcode)
    {
        if ($this->barcode == $barcode) {
            return true;
        } elseif (count($this->barcodes) > 0) {
            foreach ($this->barcodes as $productBarcode) {
                if ($productBarcode->barcode == $barcode) {
                    return true;
                }
            }
        }
        return false;
    }
}
