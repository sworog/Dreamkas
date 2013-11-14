<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Decimal;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Validator\Constraints\StoreProduct\RetailPrice as AssertRetailPrice;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property Product    $product
 * @property Store      $store
 * @property SubCategory $subCategory
 * @property Money      $retailPrice
 * @property float      $retailMarkup
 * @property string     $retailPricePreference
 * @property Money      $roundedRetailPrice
 * @property int        $inventory
 * @property float      $averageDailySales
 * @property Money      $lastPurchasePrice
 * @property Money      $averagePurchasePrice
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository"
 * )
 * @MongoDB\UniqueIndex(keys={"product"="asc", "store"="asc"})
 * @AssertRetailPrice
 */
class StoreProduct extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @var string
     * @Serializer\Exclude
     */
    protected $id;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $retailPrice;

    /**
     * @MongoDB\Float
     * @var float
     */
    protected $retailMarkup;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $retailPricePreference = Product::RETAIL_PRICE_PREFERENCE_MARKUP;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $roundedRetailPrice;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @var Product
     */
    protected $product;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var SubCategory
     * @Serializer\Exclude
     */
    protected $subCategory;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @var Store
     */
    protected $store;

    /**
     * Остаток
     * @MongoDB\Increment
     * @Serializer\Accessor(getter="getInventoryDecimal")
     * @var int
     */
    protected $inventory = 0;

    /**
     * @MongoDB\Float
     * @Serializer\Accessor(getter="getAverageDailySalesDecimal")
     * @var float
     */
    protected $averageDailySales = 0;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $lastPurchasePrice;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $averagePurchasePrice;

    /**
     * @Serializer\VirtualProperty
     * @return int
     * @deprecated
     */
    public function getAmount()
    {
        return $this->inventory;
    }

    /**
     * @return float
     */
    public function getInventoryDays()
    {
        if ($this->inventory > 0 && $this->averageDailySales > 0) {
            return $this->inventory / $this->averageDailySales;
        } else {
            return 0;
        }
    }

    /*
     * Dummy method to format values for serializer
     */

    /**
     * @Serializer\SerializedName("inventoryDays")
     * @Serializer\VirtualProperty
     * @return Decimal
     */
    public function getInventoryDaysDecimal()
    {
        return Decimal::createFromFloat($this->getInventoryDays(), 1);
    }

    /**
     * @return string
     */
    public function getAverageDailySalesDecimal()
    {
        return Decimal::createFromFloat($this->averageDailySales, 2)->toString();
    }

    /**
     * @return string
     */
    public function getInventoryDecimal()
    {
        return Decimal::createFromFloat($this->inventory, 2)->toString();
    }
}
