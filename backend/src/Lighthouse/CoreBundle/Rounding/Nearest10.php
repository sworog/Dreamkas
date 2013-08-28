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
    const NAME = 'nearest10';
    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
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
