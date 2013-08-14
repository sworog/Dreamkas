<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.rounding.nearest100")
 * @DI\Tag("rounding")
 */
class Nearest100 extends AbstractRounding
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'nearest100';
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
