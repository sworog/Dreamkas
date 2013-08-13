<?php

namespace Lighthouse\CoreBundle\Rounding;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.rounding.nearest1")
 * @DI\Tag("rounding")
 */
class Nearest1 extends AbstractRounding
{
    public function getName()
    {
        return 'nearest1';
    }

    public function round($value)
    {
        // TODO: Implement round() method.
    }
}
