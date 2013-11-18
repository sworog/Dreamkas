<?php

namespace Lighthouse\CoreBundle\Document\Sale\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Types\Decimal;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Types\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @MongoDB\Document
 *
 * @property int            $id
 * @property Money          $price
 * @property Decimal        $quantity
 * @property Money          $totalPrice
 * @property DateTime       $createdDate
 * @property ProductVersion $product
 * @property Sale           $sale
 */
class SaleProduct extends AbstractDocument implements Reasonable
{
    const REASON_TYPE = 'SaleProduct';

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Цена продажи
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank
     * @LighthouseAssert\Money(notBlank=true, zero=true)
     * @var Money
     */
    protected $price;

    /**
     * Количество
     * @MongoDB\Field(type="quantity")
     * @Assert\NotBlank
     * @LighthouseAssert\Range\Range(gt=0)
     * @var Decimal
     */
    protected $quantity;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPrice;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Version\ProductVersion",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var ProductVersion
     */
    protected $product;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Sale\Sale",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Sale
     */
    protected $sale;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Exclude
     * @var Product
     */
    protected $originalProduct;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Exclude
     * @var Store
     */
    protected $store;

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function beforeSave()
    {
        $this->totalPrice = $this->price->mul($this->quantity);

        $this->createdDate = $this->sale->createdDate;
        $this->store = $this->sale->store;
        $this->originalProduct = $this->product->getObject();
    }

    /**
     * @return string
     */
    public function getReasonId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getReasonType()
    {
        return self::REASON_TYPE;
    }

    /**
     * @return DateTime
     */
    public function getReasonDate()
    {
        return $this->createdDate;
    }

    /**
     * @return float
     */
    public function getProductQuantity()
    {
        return $this->quantity->toNumber();
    }

    /**
     * @return Product
     */
    public function getReasonProduct()
    {
        return $this->product->getObject();
    }

    /**
     * @return Money
     */
    public function getProductPrice()
    {
        return $this->price;
    }

    /**
     * @return boolean
     */
    public function increaseAmount()
    {
        return false;
    }

    /**
     * @return Storeable
     */
    public function getReasonParent()
    {
        return $this->sale;
    }

    /**
     * @param Quantity $quantity
     */
    public function setQuantity($quantity)
    {
        if (!$quantity instanceof Quantity) {
            $quantity = Quantity::createFromNumeric($quantity);
        }
        $this->quantity = $quantity;
    }
}
