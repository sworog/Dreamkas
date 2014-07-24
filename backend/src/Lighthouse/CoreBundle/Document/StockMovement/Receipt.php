<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use DateTime;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 *
 * @Unique(
 *     fields="hash",
 *     message="lighthouse.validation.errors.receipt.hash.unique",
 *     repositoryMethod="findReceiptBy"
 * )
 *
 * @property DateTime   $createdDate
 * @property string     $hash
 * @property Money      $sumTotal
 * @property int        $itemsCount
 */
class Receipt extends StockMovement
{
    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc", sparse=true)
     * @Assert\NotBlank
     * @var string
     */
    protected $hash;

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
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function prePersist()
    {
        if (empty($this->createdDate)) {
            $this->createdDate = new DateTime();
        }

        foreach ($this->products as $product) {
            $product->setReasonParent($this);
        }
    }
}
