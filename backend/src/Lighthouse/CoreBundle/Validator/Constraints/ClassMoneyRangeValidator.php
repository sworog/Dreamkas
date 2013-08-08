<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Validator\Constraints\Compare\ClassMoneyComparison;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\Comparison;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Lighthouse\CoreBundle\Types\Money as MoneyType;

class ClassMoneyRangeValidator extends MoneyRangeValidator
{
    /**
     * @param object $value
     * @param Constraint|ClassMoneyRange $constraint
     * @return ClassMoneyComparison|Compare\Comparison
     */
    protected function createComparison($value, Constraint $constraint)
    {
        return new ClassMoneyComparison($value, $constraint->field, $this->comparator);
    }

    /**
     * @param string $limit
     * @param Range|ClassMoneyRange $constraint
     * @param string $operator
     * @param Comparison|ClassMoneyComparison $comparison
     * @return string
     */
    protected function formatLimitMessage($limit, Range $constraint, $operator, Comparison $comparison)
    {
        $limitValue = $comparison->getObjectValue($limit);
        return parent::formatLimitMessage($limitValue, $constraint, $operator, $comparison);
    }
}
