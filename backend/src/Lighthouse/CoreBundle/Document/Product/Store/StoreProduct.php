<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Validator\Constraints\StoreProduct\RetailPrice as AssertRetailPrice;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property Money  $retailPrice
 * @property float  $retailMarkup
 * @property string $retailPricePreference
 * @property Money  $roundedRetailPrice
 * @property Product $product
 * @property SubCategory $subCategory
 * @property Store  $store
 * @property int    $inventory
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
     * @var int
     */
    protected $inventory = 0;

    /**
     * @MongoDB\Float
     * @var float
     */
    protected $inventoryRatio;

    /**
     * @MongoDB\Float
     * @var float
     */
    protected $averageDailySales;

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
     */
    public function getAmount()
    {
        return $this->inventory;
    }

    /**
     * @Serializer\VirtualProperty
     * @return int
     */
    public function getInventoryDays()
    {
        if ($this->inventory > 0) {
            return $this->inventory * $this->inventoryRatio;
        } else {
            return 0;
        }
    }
}
