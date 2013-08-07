<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

class Comparison
{
    /**
     * @var Comparator
     */
    protected $comparator;

    /**
     * @var numeric
     */
    protected $value;

    /**
     * @var numeric
     */
    protected $b;

    /**
     * @param numeric $value
     * @param numeric $b
     * @param Comparator $comparator
     */
    public function __construct($value, Comparator $comparator = null)
    {
        $this->comparator = ($comparator) ?: new Comparator();
        $this->value = $value;
    }

    /**
     * @param $operator
     * @return bool
     */
    public function compare($operator)
    {
        return $this->comparator->compare($this->value, $this->b, $operator);
    }
}
 