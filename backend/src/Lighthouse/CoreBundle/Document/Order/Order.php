<?php

namespace Lighthouse\CoreBundle\Document\Order;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\Order\Product\OrderProduct;
use Lighthouse\CoreBundle\Document\ReferenceCollection;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use DateTime;

/**
 * @property string $id
 * @property int $number
 * @property Store $store
 * @property Supplier $supplier
 * @property OrderProduct[]|ArrayCollection $products
 * @property DateTime $createdDate
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Order\OrderRepository"
 * )
 */
class Order extends AbstractDocument implements Storeable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @Generated(startValue=10000)
     * @var int
     */
    protected $number;

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
     * Поставщик
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Supplier\Supplier",
     *     simple=true
     * )
     * @Assert\NotBlank(message="lighthouse.validation.errors.order.supplier.empty")
     * @var Supplier
     */
    protected $supplier;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Order\Product\OrderProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="order"
     * )
     *
     * @Assert\Valid(traverse=true)
     * @Assert\Count(min=1, minMessage="lighthouse.validation.errors.order.products.empty")
     * @Serializer\MaxDepth(4)
     * @var OrderProduct[]
     */
    protected $products;

    /**
     * Дата составления накладной
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $createdDate;

    /**
     *
     */
    public function __construct()
    {
        $this->createdDate = new DateTime();
        $this->products = new ReferenceCollection($this, 'order');
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
    }
}
