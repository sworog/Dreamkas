<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property SupplierReturn   $parent
 *
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class SupplierReturnProduct extends StockMovementProduct
{
    const REASON_TYPE = 'SupplierReturnProduct';

    /**
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Money(notBlank=true, groups={"Default", "products"})
     * @var Money
     */
    protected $price;

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
    protected $parent;

    /**
     * @return boolean
     */
    public function increaseAmount()
    {
        return false;
    }
}
