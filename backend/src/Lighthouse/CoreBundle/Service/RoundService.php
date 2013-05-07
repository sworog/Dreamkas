<?php

namespace Lighthouse\CoreBundle\Service;

class RoundService
{
    /**
     * @param float $value
     * @param int $precision
     * @return float
     */
    public static function round($value, $precision = 0)
    {
        return round($value, $precision);
    }
}
