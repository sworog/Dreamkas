<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Returne;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\Product\ReturnProduct;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @property ReturnProduct[]|Collection|PersistentCollection  $products
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository")
 */
class Returne extends Receipt
{
    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Returne\Product\ReturnProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="return"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.return.product.empty")
     * @Assert\Valid(traverse=true)
     * @var ReturnProduct[]|Collection|PersistentCollection
     */
    protected $products;
}
