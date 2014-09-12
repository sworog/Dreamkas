<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\WriteOff;

use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
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
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffProduct",
     *      simple=true,
     *      cascade={"persist","remove"},
     *      mappedBy="parent"
     * )
     * @Serializer\MaxDepth(4)
     * @var WriteOffProduct[]|Collection
     */
    protected $products;
}
