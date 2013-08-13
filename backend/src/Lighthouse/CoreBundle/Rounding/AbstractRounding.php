<?php

namespace Lighthouse\CoreBundle\Rounding;

abstract class AbstractRounding
{
    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'lighthouse.rounding.' . $this->getName();
    }

    abstract public function round($value);
}
