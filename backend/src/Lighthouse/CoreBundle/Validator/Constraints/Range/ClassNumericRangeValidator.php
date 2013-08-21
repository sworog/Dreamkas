<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Validator\Constraints\Compare\ClassMoneyComparison;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\ClassNumericComparison;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\Comparison;
use Symfony\Component\Validator\Constraint;

class ClassNumericRangeValidator extends RangeValidator
{
    /**
     * @param object $value
     * @param Range|ClassNumericRange $constraint
     * @return ClassMoneyComparison|Comparison
     */
    protected function createComparison($value, Range $constraint)
    {
        return new ClassNumericComparison($value, $constraint->field, $this->comparator);
    }

    /**
     * @param string $limit
     * @param Range|ClassNumericRange $constraint
     * @param string $operator
     * @param Comparison|ClassNumericComparison $comparison
     * @return string
     */
    protected function formatLimitMessage($limit, Range $constraint, $operator, Comparison $comparison)
    {
        $limitValue = $comparison->getObjectValue($limit);
        return parent::formatLimitMessage($limitValue, $constraint, $operator, $comparison);
    }
}
