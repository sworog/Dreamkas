<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Store;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Exclude;

/**
 * @property string     $id
 * @property Store      $store
 * @property Money      $hourSum
 * @property DateTime   $dayHour
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Report\GrossSales\Store\GrossSalesStoreRepository"
 * )
 */
class GrossSalesStoreReport extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @Exclude
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var \DateTime
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
     * @Exclude
     * @var Store
     */
    protected $store;
}
