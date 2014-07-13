<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as AssertLH;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints as AssertMongoDB;
use DateTime;

/**
 * @property string     $id
 * @property Store      $store
 * @property Supplier   $supplier
 * @property Order      $order
 * @property string     $number
 * @property DateTime   $acceptanceDate
 * @property string     $accepter
 * @property string     $legalEntity
 * @property string     $supplierInvoiceNumber
 * @property Money      $sumTotal
 * @property Money      $sumTotalWithoutVAT
 * @property Money      $totalAmountVAT
 * @property int        $itemsCount
 * @property boolean    $includesVAT
 * @property Collection|InvoiceProduct[]|PersistentCollection $products
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository"
 * )
 * @AssertMongoDB\Unique(message="lighthouse.validation.errors.invoice.order.unique", fields={"order"})
 */
class Invoice extends AbstractDocument implements Storeable
{
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
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Order\Order",
     *     cascade="persist",
     *     simple=true
     * )
     *
     * @MongoDB\UniqueIndex(sparse=true)
     * @AssertLH\Reference(message="lighthouse.validation.errors.invoice.order.does_not_exists")
     * @Serializer\MaxDepth(2)
     * @var Order
     */
    protected $order;

    /**
     * @Generated(startValue=10000)
     * @var int
     */
    protected $number;

    /**
     * Поставщик
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Supplier\Supplier",
     *     simple=true
     * )
     * @Assert\NotBlank(message="lighthouse.validation.errors.invoice.supplier.empty")
     * @AssertLH\Reference(message="lighthouse.validation.errors.invoice.supplier.does_not_exists")
     * @var Supplier
     */
    protected $supplier;

    /**
     * Дата приемки
     * @MongoDB\Date
     * @Assert\NotBlank
     * @Assert\DateTime
     * @var \DateTime
     */
    protected $acceptanceDate;

    /**
     * Кто принял
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $accepter;

    /**
     * Получатель (юр. лицо)
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $legalEntity;

    /**
     * Входящий номер накладной
     * @Assert\NotBlank
     * @MongoDB\String
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $supplierInvoiceNumber;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $sumTotal;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $sumTotalWithoutVAT;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalAmountVAT;

    /**
     * @MongoDB\Boolean
     * @var bool
     */
    protected $includesVAT = true;

    /**
     * Количество позиций
     *
     * @MongoDB\Int
     * @var int
     */
    protected $itemsCount;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="invoice"
     * )
     *
     * @Assert\Valid(traverse=true)
     * @Assert\Count(
     *      min=1,
     *      minMessage="lighthouse.validation.errors.invoice.products.empty"
     * )
     * @Serializer\MaxDepth(4)
     * @var InvoiceProduct[]|Collection
     */
    protected $products;

    /**
     *
     */
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
     * @param Order $order
     */
    public function setOrder(Order $order = null)
    {
        if ($order) {
            $order->invoice = $this;
        }
        $this->order = $order;
    }

    /**
     * @param InvoiceProduct[] $products
     */
    public function setProducts($products)
    {
        foreach ($products as $product) {
            $product->invoice = $this;
        }

        $this->products = $products;
    }

    public function calculateTotals()
    {
        $this->itemsCount = count($this->products);

        $this->sumTotal = $this->sumTotal->set(0);
        $this->sumTotalWithoutVAT = $this->sumTotalWithoutVAT->set(0);
        $this->totalAmountVAT = $this->totalAmountVAT->set(0);

        foreach ($this->products as $invoiceProduct) {
            $invoiceProduct->calculateTotals();
            $this->sumTotal = $this->sumTotal->add($invoiceProduct->totalPrice);
            $this->sumTotalWithoutVAT = $this->sumTotalWithoutVAT->add($invoiceProduct->totalPriceWithoutVAT);
            $this->totalAmountVAT = $this->totalAmountVAT->add($invoiceProduct->totalAmountVAT);
        }
    }
}
