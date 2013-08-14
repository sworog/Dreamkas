<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.rounding.fixed99")
 * @DI\Tag("rounding")
 */
class Fixed99 extends AbstractRounding
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'fixed99';
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
