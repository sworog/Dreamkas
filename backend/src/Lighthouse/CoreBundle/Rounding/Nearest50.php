<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;

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
     * @param int $value
     * @return int
     */
    public function round($value)
    {
        // TODO: Implement round() method.
    }
}
