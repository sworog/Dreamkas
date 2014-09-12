<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @MongoDB\HasLifecycleCallbacks
 * @MongoDB\MappedSuperclass(
 *      repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\StockMovementProductRepository"
 * )
 *
 * @property int            $id
 * @property Money          $price
 * @property Quantity       $quantity
 * @property Money          $totalPrice
 * @property DateTime       $date
 * @property ProductVersion $product
 * @property Product        $originalProduct
 * @property StockMovement  $parent
 * @property Store          $store
 */
abstract class StockMovementProduct extends AbstractDocument implements Reasonable
{
    const REASON_TYPE = 'abstract';

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $price;

    /**
     * Количество
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Chain(
     *      groups={"Default", "products"},
     *      constraints={
     *          @LighthouseAssert\Precision(3),
     *          @LighthouseAssert\Range\Range(gt=0)
     *      }
     * )
     * @MongoDB\Field(type="quantity")
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
     * @Assert\NotBlank(groups={"Default", "products"})
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Version\ProductVersion",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\MaxDepth(3)
     * @var ProductVersion
     */
    protected $product;

    /**
     * @var StockMovement
     */
    protected $parent;

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

        $this->date = $this->parent->date;
        $this->store = $this->parent->store;
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
        return static::REASON_TYPE;
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
     * @return StockMovement
     */
    public function getReasonParent()
    {
        return $this->parent;
    }

    /**
     * @param StockMovement $parent
     */
    public function setReasonParent(StockMovement $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param Quantity $quantity
     */
    public function setQuantity(Quantity $quantity)
    {
        $this->quantity = $quantity;
    }
}
