<?php

namespace Lighthouse\CoreBundle\Document\WriteOff;

use Doctrine\Common\Collections\Collection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct;
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
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\WriteOff\WriteOffRepository"
 * )
 */
class WriteOff extends AbstractDocument implements Storeable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true
     * )
     * @Assert\NotBlank
     * @var Store
     */
    protected $store;

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
     *      targetDocument="Lighthouse\CoreBundle\Document\WriteOff\Product\WriteOffProduct",
     *      simple=true,
     *      cascade="persist",
     *      mappedBy="writeOff"
     * )
     *
     * @Assert\Valid(traverse=true)
     * @var WriteOffProduct[]|Collection
     */
    protected $products;

    /**
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
    }
}
