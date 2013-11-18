<?php

namespace Lighthouse\CoreBundle\Types;

class Quantity extends Decimal
{
    const DEFAULT_PRECISION = 3;

    /**
     * @param float|string $float
     * @param int $precision
     * @return Quantity
     */
    public static function createFromNumeric($float, $precision = self::DEFAULT_PRECISION)
    {
        return parent::createFromNumeric($float, $precision);
    }
}
 