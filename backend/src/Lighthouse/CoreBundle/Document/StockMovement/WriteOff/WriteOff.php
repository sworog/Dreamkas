<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\WriteOff;


use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\Product\WriteOffProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @property string $id
 * @property Store $store
 * @property string $number
 * @property DateTime $date
 * @property Money $sumTotal
 * @property int $itemsCount
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
     * Дата списания
     * @MongoDB\Date
     * @Assert\NotBlank
     * @Assert\DateTime
     * @var DateTime
     */
    protected $date;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $sumTotal;

    /**
     * Количество позиций
     *
     * @MongoDB\Int
     * @var int
     */
    protected $itemsCount;

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
