<?php

namespace Lighthouse\CoreBundle\Document\Returne;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Receipt\Receipt;
use Lighthouse\CoreBundle\Document\Returne\Product\ReturnProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use DateTime;

/**
 * @property ReturnProduct[]|ArrayCollection  $products
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Receipt\ReceiptRepository",
 *     collection="Receipt"
 * )
 */
class Returne extends Receipt
{
    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Returne\Product\ReturnProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="return"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.return.product.empty")
     * @Assert\Valid(traverse=true)
     * @var ReturnProduct[]|ArrayCollection
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
            $product->return = $this;
        }
    }
}
