<?php

namespace Lighthouse\CoreBundle\Document\Returne;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Returne\Product\ReturnProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use DateTime;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Returne\ReturnRepository",
 *     collection="Return"
 * )
 *
 * @Unique(fields="hash", message="lighthouse.validation.errors.return.hash.unique")
 *
 * @property int        $id
 * @property DateTime   $createdDate
 * @property string     $hash
 * @property Store      $store
 * @property ReturnProduct[]|ArrayCollection  $products
 * @property Money      $sumTotal
 * @property int        $itemsCount
 */
class Returne extends AbstractDocument implements Storeable
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
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Returne\Product\ReturnProduct",
     *      simple=true,
     *      cascade="persist"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.return.product.empty")
     * @Assert\Valid(traverse=true)
     * @var ReturnProduct[]|ArrayCollection
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
            $product->return = $this;
        }
    }
}
