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
    /**
     * @return string
     */
    public function getName()
    {
        return 'nearest50';
    }

    /**
     * @param Money $value
     * @return Money
     */
    public function round(Money $value)
    {
        return new Money($value);
    }
}
