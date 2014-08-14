<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\WriteOff;

use Lighthouse\CoreBundle\Document\SoftDeleteableDocument;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\MongoDB\Generated\Generated;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string $number
 * @property WriteOffProduct[]|Collection $products
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffRepository")
 */
class WriteOff extends StockMovement
{
    const TYPE = 'WriteOff';

    /**
     * @Generated(startValue=10000)
     * @var int
     */
    protected $number;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="writeOff"
     * )
     * @Assert\Valid(traverse=true)
     * @Assert\Count(
     *      min=1,
     *      minMessage="lighthouse.validation.errors.writeoff.products.empty"
     * )
     * @Serializer\MaxDepth(4)
     * @var WriteOffProduct[]|Collection
     */
    protected $products;

    /**
     * @param WriteOffProduct[] $products
     */
    public function setProducts($products)
    {
        foreach ($products as $product) {
            $product->setReasonParent($this);
        }

        $this->products = $products;
    }
}
