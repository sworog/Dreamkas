<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @property string     $id
 * @property Store      $store
 * @property DateTime   $date
 * @property int        $itemsCount
 * @property Money      $sumTotal
 * @property Collection|PersistentCollection|StockMovementProduct[] $products
 *
 * @MongoDB\MappedSuperclass(
 *      repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField("type")
 * @MongoDB\DiscriminatorMap({
 *      "Invoice" = "Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice",
 *      "WriteOff" = "Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff",
 *      "StockIn" = "Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockIn",
 *      "Sale" = "Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale",
 *      "Return" = "Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne",
 *      "SupplierReturn" = "Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn"
 * })
 */
abstract class StockMovement extends AbstractDocument implements Storeable
{
    const TYPE = 'abstract';

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true
     * )
     * @Assert\NotBlank
     * @Serializer\MaxDepth(2)
     * @var Store
     */
    protected $store;

    /**
     * @MongoDB\Date
     * @Assert\NotBlank
     * @Assert\DateTime
     * @var DateTime
     */
    protected $date;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $sumTotal;

    /**
     * @MongoDB\Int
     * @var int
     */
    protected $itemsCount;

    /**
     * @var Collection|PersistentCollection|StockMovementProduct[]
     */
    protected $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("type")
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function prePersist()
    {
        if (empty($this->date)) {
            $this->date = new DateTime();
        }

        foreach ($this->products as $product) {
            $product->setReasonParent($this);
        }
    }

    public function calculateTotals()
    {
        $this->itemsCount = count($this->products);

        $this->sumTotal = $this->sumTotal->set(0);

        foreach ($this->products as $product) {
            $productSumTotal = $product->calculateTotals();
            $this->sumTotal = $this->sumTotal->add($productSumTotal, Decimal::ROUND_HALF_EVEN);
        }
    }

    /**
     * @param StockMovementProduct[] $products
     */
    public function setProducts($products)
    {
        foreach ($products as $product) {
            $product->setReasonParent($this);
        }

        $this->products = $products;
    }
}
