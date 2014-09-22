<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Returne;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\NotBlank
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
            $saleProducts = $this->sale->products->filter(
                function ($element) use ($product) {
                    if ($product->product->id == $element->product->id) {
                        return true;
                    }

                    return false;
                }
            );
            if (!empty($saleProducts)) {
                $product->price = $saleProducts->first()->price;
            }
        }

        $this->products = $products;
    }
}
