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
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\DatesCompare;
use DateTime;

/**
 * @property string $id
 * @property Store  $store
 * @property string $sku
 * @property string $supplier
 * @property Collection|InvoiceProduct[] $products
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository"
 * )
 * @DatesCompare(
 *     minField="supplierInvoiceDate",
 *     maxField="acceptanceDate",
 *     message="lighthouse.validation.errors.invoice.dates_compare"
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
     * @var Store
     */
    protected $store;

    /**
     * Артикул
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $sku;

    /**
     * Поставщик
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
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
     * Дата входящей накладной
     * @MongoDB\Date
     * @Assert\DateTime
     * @var \DateTime
     */
    protected $supplierInvoiceDate;

    /**
     * Дата составления накладной
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $sumTotal;

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
     *      cascade="persist",
     *      mappedBy="invoice"
     * )
     *
     * @Assert\Valid(traverse=true)
     * @var InvoiceProduct[]
     */
    protected $products;

    /**
     *
     */
    public function __construct()
    {
        $this->products = new InvoiceProductCollection();
        $this->createdDate = new DateTime();
        $this->sumTotal = new Money(0);
    }

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
    }
}
