<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn;

use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\Product\SupplierReturnProduct;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Validator\Constraints as AssertLH;

/**
 * @property string $number
 * @property bool   $paid
 * @property Supplier $supplier
 * @property SupplierReturnProduct[]|Collection $products
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnRepository"
 * )
 */
class SupplierReturn extends StockMovement
{
    const TYPE = 'SupplierReturn';

    /**
     * @Generated(startValue=10000)
     * @var int
     */
    protected $number;

    /**
     * Поставщик
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Supplier\Supplier",
     *     simple=true
     * )
     * Assert\NotBlank(message="lighthouse.validation.errors.invoice.supplier.empty")
     * @AssertLH\Reference(message="lighthouse.validation.errors.invoice.supplier.does_not_exists")
     * @var Supplier
     */
    protected $supplier;

    /**
     * @MongoDB\Boolean
     * @var bool
     */
    protected $paid;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\Product\SupplierReturnProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="parent"
     * )
     * @Assert\Valid(traverse=true)
     * @Assert\Count(
     *      min=1,
     *      minMessage="lighthouse.validation.errors.stock_movement.products.empty"
     * )
     * @Serializer\MaxDepth(4)
     * @var SupplierReturnProduct[]|Collection
     */
    protected $products;

    /**
     * @param SupplierReturnProduct[] $products
     */
    public function setProducts($products)
    {
        foreach ($products as $product) {
            $product->setReasonParent($this);
        }

        $this->products = $products;
    }
}
