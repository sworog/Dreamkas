<?php

namespace Lighthouse\CoreBundle\Document\WriteOff\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProductRepository"
 * )
 * @property string     $id
 * @property Money      $price
 * @property Quantity   $quantity
 * @property Money      $totalPrice
 * @property DateTime   $createdDate
 * @property string     $cause
 * @property ProductVersion    $product
 * @property WriteOff   $writeOff
 */
class WriteOffProduct extends AbstractDocument implements Reasonable
{
    const REASON_TYPE = 'WriteOffProduct';

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Цена
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank
     * @LighthouseAssert\Money(notBlank=true)
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
    protected $createdDate;

    /**
     * Количество
     * @MongoDB\Field(type="quantity")
     * @Assert\NotBlank
     * @LighthouseAssert\Chain({
     *  @LighthouseAssert\Precision(3),
     *  @LighthouseAssert\Range\Range(gt=0)
     * })
     * @var Quantity
     */
    protected $quantity;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="1000", maxMessage="lighthouse.validation.errors.length")
     */
    protected $cause;

    /**
     * @Assert\NotBlank
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
     *     targetDocument="Lighthouse\CoreBundle\Document\WriteOff\WriteOff",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Serializer\MaxDepth(2)
     * @var WriteOff
     */
    protected $writeOff;

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
        $this->createdDate = $this->writeOff->date;
        $this->store = $this->writeOff->store;
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
     * @return \DateTime
     */
    public function getReasonDate()
    {
        return $this->createdDate;
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
        return $this->writeOff;
    }

    /**
     * @param Quantity $quantity
     */
    public function setQuantity(Quantity $quantity = null)
    {
        $this->quantity = $quantity;
    }
}
