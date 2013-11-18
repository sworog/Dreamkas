<?php

namespace Lighthouse\CoreBundle\Types;

interface Numeric
{
    /**
     * @return int|float
     */
    public function toNumber();

    /**
     * @return string
     */
    public function toString();
}
