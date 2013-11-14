<?php

namespace Lighthouse\CoreBundle\Types;

class Decimal
{
    const NAME = 'Decimal';

    /**
     * @var int
     */
    protected $precision;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var int
     */
    protected $divider;

    /**
     * @param int $count
     * @param int $precision
     */
    public function __construct($count, $precision)
    {
        $this->count = $count;
        $this->precision = $precision;
        $this->divider = self::getDivider($precision);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     * @return number
     */
    protected static function getDivider($precision)
    {
        return pow(10, $precision);
    }

    /**
     * @return string
     */
    public function toString()
    {
        $value = $this->count / $this->divider;
        return sprintf("%.{$this->precision}f", $value);
    }

    /**
     * @param float|string $float
     * @param int $precision
     * @return Decimal
     */
    static public function createFromFloat($float, $precision)
    {
        $count = round(self::getDivider($precision) * $float);
        return new self($count, $precision);
    }
}
