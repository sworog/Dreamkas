<?php

namespace Lighthouse\CoreBundle\Types;

class Money
{
    protected $count;

    public function __construct($count = 0)
    {
        $this->setCount($count);
    }

    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }
}
