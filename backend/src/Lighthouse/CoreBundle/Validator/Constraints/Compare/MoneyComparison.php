<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MoneyComparison extends Comparison
{
    /**
     * @var Money
     */
    protected $moneyValue;

    /**
     * @param Money|mixed $value
     * @param Comparator $comparator
     * @throws UnexpectedTypeException
     */
    public function __construct($value, Comparator $comparator = null)
    {
        $this->moneyValue = $value;
        parent::__construct($value, $comparator);
    }

    /**
     * @param mixed $value
     * @throws UnexpectedTypeException
     * @return int
     */
    protected function normalizeValue($value)
    {
        if (null === $value || ($value instanceof Money && $value->isNull())) {
            return parent::normalizeValue(null);
        } elseif ($value instanceof Money) {
            return parent::normalizeValue($value->getCount());
        }

        throw new UnexpectedTypeException($value, 'Money');
    }

    /**
     * @return Money
     */
    public function getMoneyValue()
    {
        return $this->moneyValue;
    }
}
