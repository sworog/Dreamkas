<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Sale\Product;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProductRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 *
 * @property Sale $parent
 */
class SaleProduct extends StockMovementProduct
{
    const REASON_TYPE = 'SaleProduct';

    /**
     * Цена продажи
     * @Assert\NotBlank(groups={"Default","products"})
     * @LighthouseAssert\Money(notBlank=true, zero=true,groups={"Default","products"})
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $price;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Serializer\MaxDepth(2)
     * @Assert\NotBlank
     * @var Sale
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
