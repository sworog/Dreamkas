<?php

namespace Lighthouse\ReportsBundle\Document\GrossReturn\Network;

use DateTime;
use JMS\Serializer\Annotation\Exclude;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * @property $id
 * @property DateTime $day
 * @property Money $grossReturn
 * @property Quantity $quantity
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossReturn\Network\GrossReturnNetworkRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc"})
 */
class GrossReturn extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @Exclude
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $day;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $grossReturn;

    /**
     * @MongoDB\Field(type="quantity")
     * @var Quantity
     */
    protected $quantity;
}
