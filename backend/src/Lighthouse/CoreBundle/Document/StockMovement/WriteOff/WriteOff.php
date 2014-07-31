<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\WriteOff;


use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

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
     * Номер
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $number;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct",
     *      simple=true,
     *      cascade="persist",
     *      mappedBy="writeOff"
     * )
     *
     * @Assert\Valid(traverse=true)
     * @var WriteOffProduct[]|Collection
     */
    protected $products;
}
