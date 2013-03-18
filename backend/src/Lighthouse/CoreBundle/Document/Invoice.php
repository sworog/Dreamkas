<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @MongoDB\Document
 * @Serializer\XmlRoot("invoice")
 */
class Invoice extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Поставщик
     * @MongoDB\String
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
     * @var string
     */
    protected $accepter;

    /**
     * Получатель (юр. лицо)
     * @MongoDB\String
     * @var string
     */
    protected $legalEntity;

    /**
     * Входящий номер накладной
     * @MongoDB\String
     * @var string
     */
    protected $supplierReferenceNumber;

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
            'supplier' => $this->supplier,
            'acceptanceDate' => $this->acceptanceDate,
            'accepter' => $this->accepter,
            'legalEntity' => $this->legalEntity,
            'supplierReferenceNumber' => $this->supplierReferenceNumber,
            'dateCreated' => $this->createdDate,
        );
    }
}
