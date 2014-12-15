<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesByStores;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;

class GrossSalesByStoresReport extends AbstractDocument
{
    /**
     * @var DateTime
     */
    protected $dayHour;

    /**
     * @var Money
     */
    protected $runningSum;

    /**
     * @param DateTime $dayHour
     */
    public function __construct(DateTime $dayHour)
    {
        $this->dayHour = $dayHour;
        $this->runningSum = new Money(0);
    }

    /**
     * @param Money $money
     */
    public function addRunningSum(Money $money)
    {
        $this->runningSum = $this->runningSum->add($money);
    }
}
