<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints\DatesCompare;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\InvoiceRepository"
 * )
 * @DatesCompare(
 *     firstField="acceptanceDate",
 *     secondField="supplierInvoiceDate",
 *     message="lighthouse.validation.errors.invoice.dates_compare"
 * )
 */
class Invoice extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

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
     * @MongoDB\Int
     * @var int
     */
    protected $sumTotal;

    /**
     *
     */
    public function __construct()
    {
        //$this->acceptanceDate = new \DateTime();
        $this->createdDate = new \DateTime();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'sku' => $this->sku,
            'supplier' => $this->supplier,
            'acceptanceDate' => $this->acceptanceDate,
            'accepter' => $this->accepter,
            'legalEntity' => $this->legalEntity,
            'supplierInvoiceSku' => $this->supplierInvoiceSku,
            'supplierInvoiceDate' => $this->supplierInvoiceDate,
            'createdDate' => $this->createdDate,
            'sumTotal' => $this->sumTotal,
        );
    }
}
