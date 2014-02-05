<?php

namespace Lighthouse\ReportsBundle\Document\GrossMargin;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Exclude;
use DateTime;

/**
 * @property string     $id
 * @property Money      $sum
 * @property DateTime   $date
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMargin\DayGrossMarginRepository"
 * )
 */
class DayGrossMargin extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @Exclude
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Field(type="datetime_tz")
     * @var DateTime
     */
    protected $date;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $sum;
}
