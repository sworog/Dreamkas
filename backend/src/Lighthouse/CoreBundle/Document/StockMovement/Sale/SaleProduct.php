<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Sale;

use Doctrine\MongoDB\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\ReturnProduct;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 *
 * @property Sale $parent
 * @property ReturnProduct[]|Collection|PersistentCollection $returnProducts
 */
class SaleProduct extends StockMovementProduct
{
    const TYPE = 'SaleProduct';

    /**
     * Цена продажи
     * @Assert\NotBlank(groups={"Default","products"})
     * @LighthouseAssert\Money(notBlank=true,groups={"Default","products"})
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
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Returne\ReturnProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="saleProduct"
     * )
     *
     * @Serializer\Exclude
     * @var ReturnProduct[]|Collection
     */
    protected $returnProducts;

    /**
     * @return boolean
     */
    public function increaseAmount()
    {
        return false;
    }
}
