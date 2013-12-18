<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;

/**
 * @property string         $id
 * @property Money          $hourSum
 * @property DateTime       $dayHour
 *
 * @MongoDB\MappedSuperclass
 */
abstract class GrossSalesClassifierNodeReport extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $dayHour;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $hourSum;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true
     * )
     * @var Store
     */
    protected $store;

    /**
     * @return AbstractNode
     */
    abstract public function getNode();
}
