<?php

namespace Lighthouse\CoreBundle\Types;

class Money
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @param int|Money $count
     */
    public function __construct($count = null)
    {
        $this->setCount($count);
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
            $count = $this->round($count);
        }
        $this->count = $count;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param float $count
     * @return int
     */
    public function round($count)
    {
        return (int) round($count);
    }

    /**
     * @param int|Money $count
     * @param int $quantity
     * @param bool $round
     * @return $this
     */
    public function setCountByQuantity($count, $quantity, $round = false)
    {
        if ($count instanceof self) {
            $count = $count->getCount();
        }
        $count *= $quantity;
        return $this->setCount($count, $round);
    }
}
