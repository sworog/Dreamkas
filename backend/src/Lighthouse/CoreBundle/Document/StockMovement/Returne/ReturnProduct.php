<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Returne;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleProduct;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Lighthouse\CoreBundle\Validator\Constraints\ReturnProduct\ReturnProductQuantity;
use JMS\Serializer\Annotation as Serializer;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 *
 * @property Returne $parent
 * @property SaleProduct $saleProduct
 *
 * @ReturnProductQuantity(groups={"Default", "products"})
 */
class ReturnProduct extends StockMovementProduct
{
    const TYPE = 'ReturnProduct';

    /**
     * Цена возврата
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $price;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Assert\NotBlank
     * @Serializer\MaxDepth(2)
     * @var Returne
     */
    protected $parent;

    /**
     * @MongoDB\ReferenceOne(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleProduct",
     *      simple=true,
     *      cascade="persist",
     *      inversedBy="returnProducts"
     * )
     *
     * @Serializer\Exclude
     * @var SaleProduct
     */
    protected $saleProduct;

    /**
     * @return boolean
     */
    public function increaseAmount()
    {
        return true;
    }
}
