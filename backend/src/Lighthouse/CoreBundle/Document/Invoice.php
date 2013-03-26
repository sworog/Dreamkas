<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @MongoDB\Document
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
     * @MongoDB\String
     * @var string
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
     * @MongoDB\String
     * @var string
     */
    protected $supplierInvoiceDate;

    /**
     * Дата составления накладной
     * @MongoDB\String
     * @var string
     */
    protected $createdDate;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $sumTotal;

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
