<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Types\Numeric\Numeric;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class Comparison
{
    /**
     * @var Comparator
     */
    protected $comparator;

    /**
     * @var int|float
     */
    protected $value;

    /**
     * @var bool
     */
    protected $integer = false;

    /**
     * @param mixed $value
     * @param Comparator $comparator
     * @param bool $integer
     */
    public function __construct($value, Comparator $comparator = null, $integer = false)
    {
        $this->integer = (bool) $integer;
        $this->comparator = ($comparator) ?: new Comparator();
        $this->value = $this->normalizeValue($value);
    }

    /**
     * @param int|float $test
     * @param string $operator
     * @return bool
     */
    public function compare($test, $operator)
    {
        $testValue = $this->normalizeValue($test);
        return $this->comparator->compare($this->value, $testValue, $operator);
    }

    /**
     * @param mixed $value
     * @return int|string
     * @throws UnexpectedTypeException
     */
    protected function normalizeValue($value)
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof Numeric) {
            $value = $value->toNumber();
        }
        if ($this->integer && (string) (int) $value !== (string) $value) {
            throw new UnexpectedTypeException($value, 'integer');
        }
        if (!is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'numeric');
        }
        return $value;
    }

    /**
     * @return int|float
     */
    public function getValue()
    {
        return $this->value;
    }
}
