<?php

namespace Lighthouse\CoreBundle\Document\Invoice\Product;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Lighthouse\CoreBundle\Types\Money;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string $id
 * @property int    $quantity
 * @property Money  $price
 * @property Money  $totalPrice
 * @property Invoice $invoice
 * @property ProductVersion $product
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductRepository"
 * )
 */
class InvoiceProduct extends AbstractDocument implements Reasonable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Количество
     * @MongoDB\Int
     * @Assert\NotBlank
     * @LighthouseAssert\Chain({
     *   @LighthouseAssert\NotFloat,
     *   @LighthouseAssert\Range\Range(gt=0)
     * })
     * @var int
     */
    protected $quantity;

    /**
     * Закупочная цена
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank
     * @LighthouseAssert\Money(notBlank=true)
     * @var Money
     */
    protected $price;

    /**
     * Сумма
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPrice;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Invoice\Invoice",
     *     simple=true
     * )
     * @Assert\NotBlank
     * @var Invoice
     */
    protected $invoice;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Version\ProductVersion",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Assert\NotBlank
     * @var ProductVersion
     */
    protected $product;

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateTotalPrice()
    {
        $this->totalPrice = new Money();
        $this->totalPrice->setCountByQuantity($this->price, $this->quantity, true);
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
        return 'InvoiceProduct';
    }

    /**
     * @return \DateTime
     */
    public function getReasonDate()
    {
        return $this->invoice->acceptanceDate;
    }

    /**
     * @return int
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
     * @return Storeable
     */
    public function getReasonParent()
    {
        return $this->invoice;
    }
}
