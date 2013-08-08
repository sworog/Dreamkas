<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Exception\NullValueException;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Validator\Constraints\numeric;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MoneyComparison extends Comparison
{
    /**
     * @var Money
     */
    protected $moneyValue;

    /**
     * @param Money $value
     * @param Comparator $comparator
     * @throws UnexpectedTypeException
     */
    public function __construct($value, Comparator $comparator = null)
    {
        $this->moneyValue = $value;
        parent::__construct($value, $comparator);
    }

    /**
     * @param $value
     * @return int
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeValue($value)
    {
        if (null === $value) {
            throw new NullValueException('money');
        } elseif (!$value instanceof Money) {
            throw new UnexpectedTypeException($value, 'Money');
        } elseif ($value->isNull()) {
            throw new NullValueException('money');
        }
        return parent::normalizeValue($value->getCount());
    }

    /**
     * @return \Lighthouse\CoreBundle\Types\Money
     */
    public function getMoneyValue()
    {
        return $this->moneyValue;
    }
}
