<?php

namespace Lighthouse\CoreBundle\Types;

class Money
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @param int $count
     */
    public function __construct($count = null)
    {
        $this->setCount($count);
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount($count, $round = false)
    {
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
}
