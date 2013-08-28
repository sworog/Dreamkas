<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.rounding.nearest99")
 * @DI\Tag("rounding")
 */
class Nearest99 extends AbstractRounding
{
    const NAME = 'nearest99';
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
        $rounded = round($value->getCount(), -2) - 1;
        if ($rounded < 0) {
            $rounded = 0;
        }
        return new Money($rounded);
    }
}
