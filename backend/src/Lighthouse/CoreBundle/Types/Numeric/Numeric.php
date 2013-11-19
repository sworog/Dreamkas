<?php

namespace Lighthouse\CoreBundle\Types\Numeric;

interface Numeric
{
    const TYPE = 'Numeric';

    /**
     * @return int|float
     */
    public function toNumber();

    /**
     * @return string
     */
    public function toString();
}
