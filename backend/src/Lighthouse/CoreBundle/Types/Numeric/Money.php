<?php

namespace Lighthouse\CoreBundle\Types\Numeric;

use Lighthouse\CoreBundle\Types\Nullable;

class Money extends Decimal implements Nullable
{
    /**
     * @param int $count
     * @param int $precision
     */
    public function __construct($count = null, $precision = 2)
    {
        parent::__construct($count, $precision);
    }

    /**
     * @return bool
     */
    public function isNull()
    {
        return '' === $this->count || null === $this->count;
    }

    /**
     * @param float|string $numeric
     * @param int $precision
     * @param int $roundMode
     * @return Money
     */
    public static function createFromNumeric($numeric, $precision, $roundMode = self::ROUND_HALF_UP)
    {
        if (null === $numeric || '' === $numeric) {
            return new static(null, $precision);
        } else {
            return parent::createFromNumeric($numeric, $precision, $roundMode);
        }
    }
}
