<?php

namespace Lighthouse\CoreBundle\Document\Sale;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use DateTime;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Sale\SaleRepository"
 * )
 *
 * @property int        $id
 * @property DateTime   $createdDate
 * @property SaleProduct[]|ArrayCollection  $products
 */
class Sale extends AbstractDocument implements Storeable
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
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct",
     *      simple=true,
     *      cascade="persist"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.sale.product_empty")
     * @Assert\Valid(traverse=true)
     * @var SaleProduct[]|ArrayCollection
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
            $product->sale = $this;
        }
    }
}
