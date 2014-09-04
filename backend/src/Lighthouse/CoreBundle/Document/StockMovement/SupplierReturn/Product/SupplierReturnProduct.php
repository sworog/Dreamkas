<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @property string     $id
 * @property Money      $price
 * @property Quantity   $quantity
 * @property Money      $totalPrice
 * @property DateTime   $date
 * @property ProductVersion    $product
 * @property SupplierReturn   $supplierReturn
 *
 * @MongoDB\Document(
 * repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\Product\SupplierReturnProductRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 */
class SupplierReturnProduct extends AbstractDocument implements Reasonable
{
    const REASON_TYPE = 'SupplierReturnProduct';

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Money(notBlank=true, groups={"Default", "products"})
     * @var Money
     */
    protected $price;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPrice;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $date;

    /**
     * Количество
     * @MongoDB\Field(type="quantity")
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Chain(
     *      constraints={
     *          @LighthouseAssert\Precision(3),
     *          @LighthouseAssert\Range\Range(gt=0)
     *      },
     *      groups={"Default", "products"}
     * )
     * @var Quantity
     */
    protected $quantity;

    /**
     * @Assert\NotBlank(groups={"Default", "products"})
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Version\ProductVersion",
     *     simple=true,
     *     cascade="persist"
     * )
     * @Serializer\MaxDepth(3)
     * @var ProductVersion
     */
    protected $product;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Serializer\MaxDepth(2)
     * @var SupplierReturn
     */
    protected $supplierReturn;

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
        $this->date = $this->supplierReturn->date;
        $this->store = $this->supplierReturn->store;
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
        return self::REASON_TYPE;
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
        return false;
    }

    /**
     * @return Storeable
     */
    public function getReasonParent()
    {
        return $this->supplierReturn;
    }

    /**
     * @param StockMovement|SupplierReturn $parent
     */
    public function setReasonParent(StockMovement $parent)
    {
        $this->supplierReturn = $parent;
    }

    /**
     * @param Quantity $quantity
     */
    public function setQuantity(Quantity $quantity = null)
    {
        $this->quantity = $quantity;
    }
}
