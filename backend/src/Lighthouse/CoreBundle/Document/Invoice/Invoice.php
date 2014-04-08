<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProductCollection;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\DatesCompare;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @property string     $id
 * @property Store      $store
 * @property string     $number
 * @property string     $supplier
 * @property DateTime   $acceptanceDate
 * @property string     $accepter
 * @property string     $legalEntity
 * @property string     $supplierInvoiceSku
 * @property Money      $sumTotal
 * @property Money      $sumTotalWithoutVAT
 * @property Money      $totalAmountVAT
 * @property int        $itemsCount
 * @property boolean    $includesVAT
 * @property Collection|InvoiceProduct[] $products
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository"
 * )
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
     * @MongoDB\String
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $supplierInvoiceSku;

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
     * @var
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
     * @@Assert\Count(min=1, minMessage="lighthouse.validation.errors.invoice.products.empty")
     * @Serializer\MaxDepth(4)
     * @var InvoiceProduct[]
     */
    protected $products;

    /**
     *
     */
    public function __construct()
    {
        $this->products = new InvoiceProductCollection();
        $this->sumTotal = new Money(0);
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
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
}
