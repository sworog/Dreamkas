<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Returne;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as AssertLH;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property ReturnProduct[]|Collection|PersistentCollection  $products
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository")
 */
class Returne extends Receipt
{
    const TYPE = 'Return';

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Returne\ReturnProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="parent"
     * )
     * @var ReturnProduct[]|Collection|PersistentCollection
     */
    protected $products;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale",
     *     simple=true,
     *     cascade="persist"
     * )
     * @Assert\NotBlank(message="lighthouse.validation.errors.return.sale.empty")
     * @AssertLH\Reference(message="lighthouse.validation.errors.return.sale.does_not_exists")
     * @Serializer\MaxDepth(2)
     * @var Sale
     */
    protected $sale;

    /**
     * @param ReturnProduct[] $products
     */
    public function setProducts($products)
    {
        foreach ($products as $product) {
            $product->parent = $this;
        }

        $this->products = $products;
    }

    public function calculateTotals()
    {
        $this->itemsCount = count($this->products);

        $this->sumTotal = $this->sumTotal->set(0);

        foreach ($this->products as $product) {
            if ($this->sale && $this->sale->products) {
                $saleProducts = $this->sale->products->filter(
                    function ($element) use ($product) {
                        if ($product->product
                            && $element->product
                            && $product->product->id == $element->product->id
                        ) {
                            return true;
                        }

                        return false;
                    }
                );
                if (!$saleProducts->isEmpty()) {
                    $product->price = $saleProducts->first()->price;
                }
            }

            $productSumTotal = $product->calculateTotals();
            $this->sumTotal = $this->sumTotal->add($productSumTotal, Decimal::ROUND_HALF_EVEN);
        }
    }
}
