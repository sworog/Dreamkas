<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Productable;
use Lighthouse\CoreBundle\Types\Money;

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
}
