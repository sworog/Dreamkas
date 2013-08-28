<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.rounding.nearest50")
 * @DI\Tag("rounding")
 */
class Nearest50 extends AbstractRounding
{
    const NAME = 'nearest50';
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
        $rounded = round($value->getCount() / 5, -1) * 5;
        return new Money($rounded);
    }
}
