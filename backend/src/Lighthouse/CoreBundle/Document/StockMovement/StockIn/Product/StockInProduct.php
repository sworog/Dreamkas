<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\StockIn\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockIn;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @property StockIn   $parent
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\StockIn\Product\StockInProductRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 */
class StockInProduct extends StockMovementProduct
{
    const REASON_TYPE = 'StockInProduct';

    /**
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank(groups={"Default", "products"})
     * @LighthouseAssert\Money(notBlank=true, groups={"Default", "products"})
     * @var Money
     */
    protected $price;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockIn",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     * @Serializer\MaxDepth(2)
     * @var StockIn
     */
    protected $parent;

    /**
     * @return boolean
     */
    public function increaseAmount()
    {
        return true;
    }
}
