<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Validator\Constraints\Compare\ClassMoneyComparison;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\Comparison;
use Lighthouse\CoreBundle\Validator\Constraints\Compare;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Lighthouse\CoreBundle\Types\Money as MoneyType;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.validator.class_money_range")
 * @DI\Tag("validator.constraint_validator", attributes={"alias"="class_money_range_validator"})
 */
class ClassMoneyRangeValidator extends MoneyRangeValidator
{
    /**
     * @param object $value
     * @param Constraint|ClassMoneyRange $constraint
     * @return ClassMoneyComparison|Compare\Comparison
     */
    protected function createComparison($value, Range $constraint)
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
