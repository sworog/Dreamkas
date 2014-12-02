<?php

namespace Lighthouse\CoreBundle\Document\CashFlow;

use DateTime;
use Lighthouse\CoreBundle\Types\Numeric\Money;

interface CashFlowable
{
    /**
     * @return bool
     */
    public function cashFlowNeeded();

    /**
     * @return string
     */
    public function getCashFlowReasonType();

    /**
     * @return Money
     */
    public function getCashFlowAmount();

    /**
     * @return string
     */
    public function getCashFlowDirection();
}
