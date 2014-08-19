<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Product\Productable;
use Lighthouse\CoreBundle\Document\Store\Storeable;
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
     * @return Storeable
     */
    public function getReasonParent();

    /**
     * @param Storeable $parent
     */
    public function setReasonParent(Storeable $parent);

    /**
     * @return Money|null
     */
    public function calculateTotals();
}
