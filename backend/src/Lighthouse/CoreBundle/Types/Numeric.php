<?php

namespace Lighthouse\CoreBundle\Types;

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
