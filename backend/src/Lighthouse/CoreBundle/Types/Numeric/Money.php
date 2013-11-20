<?php

namespace Lighthouse\CoreBundle\Types\Numeric;

use Lighthouse\CoreBundle\Service\RoundService;
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
     * @param int  $count
     * @param bool $round
     * @return $this
     */
    public function setCount($count, $round = false)
    {
        if ($count instanceof self) {
            $count = $count->getCount();
        }
        if ($round) {
            $count = (int) RoundService::round($count);
        }
        $this->count = $count;
        return $this;
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
     * @return Decimal
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
