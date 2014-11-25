<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Invoice;

use Doctrine\Bundle\MongoDBBundle\Validator\Constraints as AssertMongoDB;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Exception\HasDeletedException;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as AssertLH;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property Supplier   $supplier
 * @property Order      $order
 * @property string     $number
 * @property string     $accepter
 * @property bool       $paid
 * @property string     $legalEntity
 * @property string     $supplierInvoiceNumber
 * @property Money      $sumTotalWithoutVAT
 * @property Money      $totalAmountVAT
 * @property boolean    $includesVAT
 * @property Collection|InvoiceProduct[]|PersistentCollection $products
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceRepository")
 * @AssertMongoDB\Unique(message="lighthouse.validation.errors.invoice.order.unique", fields={"order"})
 */
class Invoice extends StockMovement
{
    const TYPE = 'Invoice';

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
     * @AssertLH\Reference(message="lighthouse.validation.errors.invoice.supplier.does_not_exists")
     * @AssertLH\NotDeleted(true, isDeletedMessage="lighthouse.validation.errors.deleted.supplier.forbid.edit")
     * @var Supplier
     */
    protected $supplier;

    /**
     * @MongoDB\Boolean
     * @var bool
     */
    protected $paid;

    /**
     * Кто принял
     * @MongoDB\String
     * Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $accepter;

    /**
     * Получатель (юр. лицо)
     * @MongoDB\String
     * Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $legalEntity;

    /**
     * Входящий номер накладной
     * Assert\NotBlank
     * @MongoDB\String
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $supplierInvoiceNumber;

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
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="parent"
     * )
     * @Serializer\MaxDepth(4)
     * @var InvoiceProduct[]|Collection
     */
    protected $products;

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
