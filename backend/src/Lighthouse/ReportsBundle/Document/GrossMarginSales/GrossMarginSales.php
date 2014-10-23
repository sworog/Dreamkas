<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Exclude;
use DateTime;

/**
 * @property string         $id
 * @property Money          $grossSales
 * @property Money          $grossMargin
 * @property Money          $costOfGoods
 * @property Quantity       $quantity
 * @property DateTime       $day
 * @property Store          $store
 *
 * @MongoDB\MappedSuperclass
 * @MongoDB\HasLifecycleCallbacks
 * @MongoDB\InheritanceType("COLLECTION_PER_CLASS")
 */
abstract class GrossMarginSales extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @Exclude
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $day;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $grossSales;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $grossMargin;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $costOfGoods;

    /**
     * @MongoDB\Field(type="quantity")
     * @var Quantity
     */
    protected $quantity;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true
     * )
     * @var Store
     */
    protected $store;

    /**
     * @return object
     */
    abstract public function getItem();

    /**
     * @MongoDB\PrePersist
     */
    public function prePersist()
    {
        $this->id = $this->day->getTimestamp();
        if ($this->store) {
            $this->id.= ':' . $this->store->id;
        }
        if ($this->getItem()) {
            $this->id.= ':' . $this->getItem()->id;
        }
    }
}
