<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Product\Productable;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovement;
use Lighthouse\CoreBundle\Types\Numeric\Money;

interface Reasonable extends Productable
{
    /**
     * @return string
     */
    public function getReasonId();

    /**
     * @return string
     */
    public function getReasonType();

    /**
     * @return \DateTime
     */
    public function getReasonDate();

    /**
     * @return StockMovement
     */
    public function getReasonParent();

    /**
     * @param StockMovement $parent
     */
    public function setReasonParent(StockMovement $parent);

    /**
     * @return Money|null
     */
    public function calculateTotals();
}
