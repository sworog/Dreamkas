<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.rounding.nearest1")
 * @DI\Tag("rounding")
 */
class Nearest1 extends AbstractRounding
{
    const NAME = 'nearest1';
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
        $rounded = round($value->getCount());
        return new Money($rounded);
    }
}
