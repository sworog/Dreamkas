<?php

namespace Lighthouse\CoreBundle\Util\Iterator;

class ArrayIterator extends \ArrayIterator
{
    /**
     * Workaround for missing usort function in ArrayIterator
     * @param $callable
     * @return $this
     */
    public function usort($callable)
    {
        $values = $this->getArrayCopy();
        usort($values, $callable);
        foreach ($values as $offset => $value) {
            $this->offsetSet($offset, $value);
        }
    }
}
