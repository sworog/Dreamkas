<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MoneyCompareValidator extends CompareValidator
{
    /**
     * @param Money $value
     * @return int
     * @throws UnexpectedTypeException
     */
    protected function normalizeFieldValue($value)
    {
        if (!$value instanceof Money) {
            throw new UnexpectedTypeException($value, 'Money');
        }

        return $value->toNumber();
    }

    /**
     * @param Money $value
     * @param Constraint|MoneyCompare $constraint
     * @return string
     */
    protected function formatMessageValue($value, Constraint $constraint)
    {
        return $value->toString();
    }
}
