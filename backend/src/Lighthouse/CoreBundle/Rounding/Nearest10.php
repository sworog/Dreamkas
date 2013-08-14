<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.rounding.nearest10")
 * @DI\Tag("rounding")
 */
class Nearest10 extends AbstractRounding
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'nearest10';
    }

    /**
     * @param Money $value
     * @return Money
     */
    public function round(Money $value)
    {
        $rounded = round($value->getCount(), -1);
        return new Money($rounded);
    }
}
