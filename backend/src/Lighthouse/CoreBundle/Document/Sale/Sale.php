<?php

namespace Lighthouse\CoreBundle\Document\Sale;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Receipt\Receipt;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use DateTime;

/**
 * @property SaleProduct[]|ArrayCollection  $products
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Receipt\ReceiptRepository",
 *     collection="Receipt"
 * )
 */
class Sale extends Receipt
{
    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="sale"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.sale.product.empty")
     * @Assert\Valid(traverse=true)
     * @var SaleProduct[]|ArrayCollection
     */
    protected $products;

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
