<?php

namespace Lighthouse\CoreBundle\Document\Product\Barcode;

use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property string $barcode
 * @property Quantity $quantity
 * @property Money $price
 *
 * @MongoDB\EmbeddedDocument
 */
class Barcode extends AbstractDocument
{
    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(sparse=true)
     * @Assert\NotBlank
     * @Assert\Length(max="200", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $barcode;

    /**
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
     * @MongoDB\Field(type="money")
     * @LighthouseAssert\Money
     * @var Money
     */
    protected $price;
}
