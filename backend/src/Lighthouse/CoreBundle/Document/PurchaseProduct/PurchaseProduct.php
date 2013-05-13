<?php

namespace Lighthouse\CoreBundle\Document\PurchaseProduct;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @MongoDB\EmbeddedDocument
 */
class PurchaseProduct extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $sellingPrice;

    /**
     * @MongoDB\Int
     * @var int
     */
    protected $quantity;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalSellingPrice;

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
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateTotalSellingPrice()
    {
        $this->totalSellingPrice = new Money();
        $this->totalSellingPrice->setCountByQuantity($this->sellingPrice, $this->quantity, true);
    }
}
