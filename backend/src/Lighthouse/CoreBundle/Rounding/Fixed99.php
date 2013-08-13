<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;

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
     * @param int $value
     * @return int
     */
    public function round($value)
    {
        // TODO: Implement round() method.
    }
}
