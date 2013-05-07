<?php

namespace Lighthouse\CoreBundle\Service;

class RoundService
{
    public static function round($value, $precision = 0)
    {
        return round($value, $precision);
    }
}