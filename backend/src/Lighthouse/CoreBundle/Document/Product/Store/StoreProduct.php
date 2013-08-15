<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Validator\Constraints\StoreProduct\RetailPrice as AssertRetailPrice;
use JMS\Serializer\Annotation\Exclude;

/**
 * @property Money $retailPrice
 * @property float $retailMarkup
 * @property string $retailPricePreference
 * @property Product $product
 * @property Store $store
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
     * @MongoDB\Id
     * @var string
     * @Exclude
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
     * @Exclude
     * @var SubCategory
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
}
