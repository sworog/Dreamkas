<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Versionable\VersionableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints\Product\RetailPrice as AssertProductRetailPrice;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use JMS\Serializer\Annotation\Exclude;

/**
 *
 * @property string $id
 * @property string $name
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
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Product\ProductRepository"
 * )
 * @MongoDB\InheritanceType("COLLECTION_PER_CLASS")
 * @Unique(fields="sku", message="lighthouse.validation.errors.product.sku.unique")
 * @AssertProductRetailPrice
 */
class Product extends AbstractDocument implements VersionableInterface
{
    const RETAIL_PRICE_PREFERENCE_PRICE = 'retailPrice';
    const RETAIL_PRICE_PREFERENCE_MARKUP = 'retailMarkup';

    const UNITS_KG = 'kg';
    const UNITS_LITER = 'liter';
    const UNITS_UNIT = 'unit';

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
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\String
     * @Assert\NotBlank(message="lighthouse.validation.errors.product.units.blank")
     */
    protected $units;

    /**
     * @MongoDB\Int
     * @Assert\NotBlank(message="lighthouse.validation.errors.product.vat.blank")
     * @Assert\Range(min="0")
     */
    protected $vat;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $purchasePrice;

    /**
     * @MongoDB\String
     * @Assert\Length(max="200", maxMessage="lighthouse.validation.errors.length")
     */
    protected $barcode;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     */
    protected $sku;

    /**
     * @MongoDB\String
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     */
    protected $vendorCountry;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     */
    protected $vendor;

    /**
     * @MongoDB\String
     * @Assert\Length(max="2000", maxMessage="lighthouse.validation.errors.length")
     */
    protected $info;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $retailPriceMin;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $retailPriceMax;

    /**
     * @MongoDB\Float
     * @var float
     */
    protected $retailMarkupMin;

    /**
     * @MongoDB\Float
     * @var float
     */
    protected $retailMarkupMax;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $retailPricePreference = self::RETAIL_PRICE_PREFERENCE_MARKUP;

    /**
     * @var AbstractRounding
     */
    protected $rounding;

    /**
     * @Exclude
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
     * @var SubCategory
     */
    protected $subCategory;

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
        return 'Lighthouse\\CoreBundle\\Document\\Product\\Version\\ProductVersion';
    }
}
