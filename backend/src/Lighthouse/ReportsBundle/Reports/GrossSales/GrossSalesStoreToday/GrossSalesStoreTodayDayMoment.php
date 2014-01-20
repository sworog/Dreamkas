<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesStoreToday;

use DateTime;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;

/**
 * @property DateTime $date
 * @property Money $value
 * @property Decimal $diff
 */
class GrossSalesStoreTodayDayMoment extends AbstractDocument
{
    /**
     * @var DateTime
     */
    protected $date = null;

    /**
     * @var Money
     */
    protected $value;

    /**
     * @var null|Decimal
     */
    protected $diff = null;

    /**
     *
     */
    public function __construct()
    {
        $this->value = new Money(0);
    }

    /**
     * @param Money $money
     */
    public function addValue(Money $money)
    {
        $this->value = $this->value->add($money);
    }
}
