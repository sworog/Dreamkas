<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\StockIn;

use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInProduct;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string $number
 * @property StockInProduct[]|Collection $products
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInRepository")
 */
class StockIn extends StockMovement
{
    const TYPE = 'StockIn';

    /**
     * @Generated(startValue=10000)
     * @var int
     */
    protected $number;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="parent"
     * )
     * @Serializer\MaxDepth(4)
     * @var StockInProduct[]|Collection
     */
    protected $products;

    /**
     * @param StockInProduct[] $products
     */
    public function setProducts($products)
    {
        foreach ($products as $product) {
            $product->setReasonParent($this);
        }

        $this->products = $products;
    }
}
