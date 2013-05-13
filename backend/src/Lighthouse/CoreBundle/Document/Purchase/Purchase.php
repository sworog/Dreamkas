<?php

namespace Lighthouse\CoreBundle\Document\Purchase;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\PurchaseProduct\PurchaseProduct;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Purchase\PurchaseRepository"
 * )
 */
class Purchase extends AbstractDocument
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
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\PurchaseProduct\PurchaseProduct",
     *      simple=true,
     *      cascade="persist"
     * )
     * @var PurchaseProduct[]
     */
    protected $products = array();

    /**
     * @param PurchaseProduct $product
     */
    public function addProduct(PurchaseProduct $product)
    {
        $this->products[] = $product;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function prePersist()
    {
        if (empty($this->createdDate)) {
            $this->createdDate = new \DateTime();
        }

        foreach ($this->products as $product) {
            $product->purchase = $this;
        }
    }
}
