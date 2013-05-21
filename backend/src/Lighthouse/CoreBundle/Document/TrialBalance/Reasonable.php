<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Types\Money;

interface Reasonable
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
     * @return int
     */
    public function getReasonQuantity();

    /**
     * @return Product
     */
    public function getReasonProduct();

    /**
     * @return Money
     */
    public function getReasonPrice();
}
