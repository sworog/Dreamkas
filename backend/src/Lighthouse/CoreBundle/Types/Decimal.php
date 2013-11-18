<?php

namespace Lighthouse\CoreBundle\Types;

class Decimal implements Numeric
{
    const NAME = 'Decimal';

    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

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
        $this->divider = static::getDivider($precision);
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
     * @return float
     */
    public function toNumber()
    {
        return (float) $this->toString();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return bcdiv($this->count, $this->divider, $this->precision);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param string|Numeric|float|int $operand
     * @param int $roundMode
     * @return Decimal
     */
    public function mul($operand, $roundMode = self::ROUND_HALF_UP)
    {
        return static::calc('bcmul', $this->toString(), $operand, $this->precision, $roundMode);
    }

    /**
     * @param string|Numeric|float|int $operand
     * @param int $roundMode
     * @return Decimal
     */
    public function div($operand, $roundMode = self::ROUND_HALF_UP)
    {
        return static::calc('bcdiv', $this->toString(), $operand, $this->precision, $roundMode);
    }

    /**
     * @param string $operation
     * @param string $operandLeft
     * @param string $operandRight
     * @param int $precision
     * @param int $roundMode
     * @internal param string $operand
     * @return Decimal
     */
    public static function calc($operation, $operandLeft, $operandRight, $precision, $roundMode = self::ROUND_HALF_UP)
    {
        $result = call_user_func($operation, (string) $operandLeft, (string) $operandRight, $precision + 1);
        $rounded = static::round($result, $precision, $roundMode);
        return static::createFromNumeric($rounded, $precision);
    }

    /**
     * @param string $operand
     * @param int $precision
     * @param int $roundMode
     * @return string
     */
    public static function round($operand, $precision, $roundMode = self::ROUND_HALF_UP)
    {
        $roundPrecision = $precision + 1;
        switch ($roundMode) {
            case self::ROUND_HALF_UP:
                $rounder = bcdiv('5', self::getDivider($roundPrecision), $roundPrecision);
                break;
            case self::ROUND_HALF_DOWN;
            default:
                $rounder = '0';
                break;
        }
        if (-1 == bccomp($operand, '0', $roundPrecision)) {
            $rounder = bcdiv($rounder, '-1', $roundPrecision);
        }
        return bcadd((string) $operand, $rounder, $precision);
    }

    /**
     * @param float|string $float
     * @param int $precision
     * @return Decimal
     */
    public static function createFromNumeric($float, $precision)
    {
        $result = bcmul((string) $float, static::getDivider($precision), $precision + 1);
        $count = (int) static::round($result, 0);
        return new static($count, $precision);
    }
}
