<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Returne\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\Returne\Product\ReturnProductRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 *
 * @property int            $id
 * @property Money          $price
 * @property Quantity       $quantity
 * @property Money          $totalPrice
 * @property DateTime       $date
 * @property ProductVersion $product
 * @property Returne        $return
 */
class ReturnProduct extends AbstractDocument implements Reasonable
{
    const REASON_TYPE = 'ReturnProduct';

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
     * @var Quantity
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
    protected $date;

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
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @var Returne
     */
    protected $return;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Product
     * @Serializer\Exclude
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
        $this->calculateTotals();

        $this->date = $this->return->date;
        $this->store = $this->return->store;
        $this->originalProduct = $this->product->getObject();
    }

    /**
     * @return Money|null
     */
    public function calculateTotals()
    {
        if ($this->price) {
            $this->totalPrice = $this->price->mul($this->quantity, Decimal::ROUND_HALF_EVEN);
        }

        return $this->totalPrice;
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
        return 'ReturnProduct';
    }

    /**
     * @return \DateTime
     */
    public function getReasonDate()
    {
        return $this->date;
    }

    /**
     * @return Quantity
     */
    public function getProductQuantity()
    {
        return $this->quantity;
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
        return true;
    }

    /**
     * @return Returne
     */
    public function getReasonParent()
    {
        return $this->return;
    }

    /**
     * @param Storeable|Returne $parent
     */
    public function setReasonParent(Storeable $parent)
    {
        $this->return = $parent;
    }

    /**
     * @param Quantity $quantity
     */
    public function setQuantity(Quantity $quantity)
    {
        $this->quantity = $quantity;
    }
}
