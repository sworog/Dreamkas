<?php

namespace Lighthouse\CoreBundle\Document\WriteOffProduct;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property int        $id
 * @property Money      $price
 * @property int        $quantity
 * @property Money      $totalPrice
 * @property \DateTime   $createdDate
 * @property Product    $product
 * @property WriteOff   $writeOff
 */
class WriteOffProduct extends AbstractDocument
{
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
     * @MongoDB\String
     */
    protected $cause;

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
     *     targetDocument="Lighthouse\CoreBundle\Document\WriteOff/WriteOff",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var WriteOff
     */
    protected $writeOff;
}
