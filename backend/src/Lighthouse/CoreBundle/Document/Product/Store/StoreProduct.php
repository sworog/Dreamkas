<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\CoreBundle\Validator\Constraints\StoreProduct\RetailPrice as AssertRetailPrice;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string     $id
 * @property Product    $product
 * @property Store      $store
 * @property SubCategory $subCategory
 * @property Money      $retailPrice
 * @property float      $retailMarkup
 * @property string     $retailPricePreference
 * @property Money      $roundedRetailPrice
 * @property Quantity   $inventory
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
     * @Serializer\Groups({"Default", "Collection"})
     * @var Money
     */
    protected $retailPrice;

    /**
     * @MongoDB\Float
     * @var float
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $retailMarkup;

    /**
     * @MongoDB\String
     * @var string
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $retailPricePreference = Product::RETAIL_PRICE_PREFERENCE_MARKUP;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $roundedRetailPrice;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Groups({"Default", "Collection"})
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
     * @Serializer\Groups({"Default"})
     */
    protected $store;

    /**
     * Остаток
     * @MongoDB\Increment
     * @Serializer\Accessor(getter="getInventory")
     * @var int
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $inventory = 0;

    /**
     * @MongoDB\Float
     * @Serializer\Accessor(getter="getAverageDailySalesFloat")
     * @var float
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $averageDailySales = 0;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $lastPurchasePrice;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     * @Serializer\Groups({"Default", "Collection"})
     */
    protected $averagePurchasePrice;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"Default", "Collection"})
     * @return int
     * @deprecated
     */
    public function getAmount()
    {
        return $this->getInventory();
    }

    /**
     * @return float
     */
    public function getInventoryDays()
    {
        if ($this->inventory > 0 && $this->averageDailySales > 0) {
            return $this->getInventory()->div($this->getAverageDailySalesDecimal())->toNumber();
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
     * @Serializer\Groups({"Default", "Collection"})
     * @return Decimal
     */
    public function getInventoryDaysDecimal()
    {
        return Decimal::createFromNumeric($this->getInventoryDays(), 1);
    }

    /**
     * @return float
     */
    public function getAverageDailySalesFloat()
    {
        return Decimal::createFromNumeric($this->averageDailySales, 2)->toNumber();
    }

    public function getAverageDailySalesDecimal()
    {
        return Decimal::createFromNumeric($this->averageDailySales, 2);
    }

    /**
     * @return Quantity
     */
    public function getInventory()
    {
        return new Quantity($this->inventory, 3);
    }

    /**
     * @param Quantity $value
     */
    public function setInventory(Quantity $value)
    {
        $this->inventory = $value->getCount();
    }
}
