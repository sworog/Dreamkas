<?php

namespace Lighthouse\CoreBundle\Document\PurchaseProduct;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Purchase\Purchase;
use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @MongoDB\Document
 *
 * @property int        $id
 * @property Money      $sellingPrice
 * @property int        $quantity
 * @property Money      $totalSellingPrice
 * @property DateTime   $createdDate
 * @property Product    $product
 * @property Purchase   $purchase
 */
class PurchaseProduct extends AbstractDocument
{
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
    protected $sellingPrice;

    /**
     * Количество
     * @MongoDB\Int
     * @Assert\NotBlank
     * @LighthouseAssert\Chain({
     *   @LighthouseAssert\NotFloat,
     *   @LighthouseAssert\Range(gt=0)
     * })
     * @var int
     */
    protected $quantity;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalSellingPrice;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Product
     */
    protected $product;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Purchase\Purchase",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Purchase
     */
    protected $purchase;

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateTotalSellingPrice()
    {
        $this->totalSellingPrice = new Money();
        $this->totalSellingPrice->setCountByQuantity($this->sellingPrice, $this->quantity, true);

        $this->createdDate = $this->purchase->createdDate;
    }
}
