<?php

namespace Lighthouse\CoreBundle\Document\Report\Store;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property string     $id
 * @property Store      $store
 * @property Money      $value
 * @property DateTime   $dayHour
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Report\Store\StoreGrossSalesRepository"
 * )
 */
class StoreGrossSalesReport extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
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
    protected $value;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true
     * )
     * @var Store
     */
    protected $store;
}
