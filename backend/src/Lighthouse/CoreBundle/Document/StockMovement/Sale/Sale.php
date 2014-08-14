<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Sale;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProduct;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @property SaleProduct[]|Collection|PersistentCollection  $products
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository"
 * )
 */
class Sale extends Receipt
{
    const TYPE = 'Sale';

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="sale"
     * )
     *
     * @Assert\NotBlank(message="lighthouse.validation.errors.sale.product.empty")
     * @Assert\Valid(traverse=true)
     * @var SaleProduct[]|Collection
     */
    protected $products;
}
