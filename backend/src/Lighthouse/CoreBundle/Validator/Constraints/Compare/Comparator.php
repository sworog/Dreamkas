<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

class Comparator
{
    const LT = 'lt';
    const GT = 'gt';
    const GTE = 'gte';
    const LTE = 'lte';
    const EQ = 'eq';
    const NEQ = 'neq';

    /**
     * @param $a
     * @param $b
     * @param string $operator
     * @return bool
     */
    public function compare($a, $b, $operator)
    {
        $method = $this->getMethod($operator);
        return $this->$method($a, $b);
    }

    /**
     * @param $operator
     * @return mixed
     */
    protected function getMethod($operator)
    {
        return $operator;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function lt($a, $b)
    {
        return $a < $b;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function lte($a, $b)
    {
        return $a <= $b;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function gt($a, $b)
    {
        return $a > $b;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function gte($a, $b)
    {
        return $a >= $b;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function eq($a, $b)
    {
        return $a == $b;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function neq($a, $b)
    {
        return $a != $b;
    }
}
