<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;

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
     * @param int $value
     * @return int
     */
    public function round($value)
    {
        // TODO: Implement round() method.
    }
}
