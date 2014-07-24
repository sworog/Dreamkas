<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string $id
 * @property Store $store
 * @property Reasonable[]|ArrayCollection|PersistentCollection $products
 *
 * @MongoDB\MappedSuperclass(
 *      repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository"
 * )
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField("type")
 * @MongoDB\DiscriminatorMap({
 *      "Invoice" = "Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice",
 *      "WriteOff" = "Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff",
 *      "Sale" = "Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale",
 *      "Return" = "Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne"
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
     * @var ArrayCollection|Reasonable[]
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
}
