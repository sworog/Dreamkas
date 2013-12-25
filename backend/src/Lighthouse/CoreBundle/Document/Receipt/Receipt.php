<?php

namespace Lighthouse\CoreBundle\Document\Receipt;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use DateTime;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Receipt\ReceiptRepository",
 *     collection="Receipt"
 * )
 *
 * @Unique(
 *     fields="hash",
 *     message="lighthouse.validation.errors.receipt.hash.unique",
 *     repositoryMethod="findReceiptBy"
 * )
 *
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="type")
 * @MongoDB\DiscriminatorMap({
 *      "sale"="Lighthouse\CoreBundle\Document\Sale\Sale",
 *      "returne"="Lighthouse\CoreBundle\Document\Returne\Returne",
 * })
 *
 * @property int        $id
 * @property DateTime   $createdDate
 * @property string     $hash
 * @property Store      $store
 * @property ArrayCollection  $products
 * @property Money      $sumTotal
 * @property int        $itemsCount
 */
class Receipt extends AbstractDocument implements Storeable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc")
     * @Assert\NotBlank
     * @var string
     */
    protected $hash;

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
     * @var ArrayCollection
     */
    protected $products;

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
}
