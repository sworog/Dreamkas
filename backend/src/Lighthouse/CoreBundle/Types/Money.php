<?php

namespace Lighthouse\CoreBundle\Types;

use Lighthouse\CoreBundle\Service\RoundService;

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
}
