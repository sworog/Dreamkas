<?php

namespace Lighthouse\CoreBundle\Document\Restitution;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Restitution\Product\RestitutionProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use DateTime;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Restitution\RestitutionRepository"
 * )
 *
 * @Unique(fields="hash", message="lighthouse.validation.errors.backoff.hash.unique")
 *
 * @property int        $id
 * @property DateTime   $createdDate
 * @property string     $hash
 * @property Store      $store
 * @property RestitutionProduct[]|ArrayCollection  $products
 */
class Restitution extends AbstractDocument implements Storeable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var \DateTime
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
     *      targetDocument="Lighthouse\CoreBundle\Document\Restitution\Product\RestitutionProduct",
     *      simple=true,
     *      cascade="persist"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.backoff.product.empty")
     * @Assert\Valid(traverse=true)
     * @var RestitutionProduct[]|ArrayCollection
     */
    protected $products = array();

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
            $product->backOff = $this;
        }
    }
}
