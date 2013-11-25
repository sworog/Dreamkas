<?php

namespace Lighthouse\CoreBundle\Rounding;

use Lighthouse\CoreBundle\Types\Numeric\Money;

abstract class AbstractRounding
{
    const TYPE = 'Rounding';

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'lighthouse.rounding.' . $this->getName();
    }

    /**
     * @param Money $value
     * @return Money
     */
    abstract public function round(Money $value);
}
