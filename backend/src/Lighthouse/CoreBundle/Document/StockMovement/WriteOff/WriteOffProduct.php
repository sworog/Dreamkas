<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\WriteOff;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string     $cause
 * @property WriteOff   $parent
 *
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class WriteOffProduct extends StockMovementProduct
{
    const REASON_TYPE = 'WriteOffProduct';

    /**
     * @MongoDB\String
     * @Assert\NotBlank(groups={"Default", "products"})
     * @Assert\Length(
     *      max="1000",
     *      maxMessage="lighthouse.validation.errors.length",
     *      groups={"Default", "products"}
     * )
     */
    protected $cause;

    /**
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Money(notBlank=true,groups={"Default", "products"})
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $price;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Serializer\MaxDepth(2)
     * @var WriteOff
     */
    protected $parent;

    /**
     * @return boolean
     */
    public function increaseAmount()
    {
        return false;
    }
}
