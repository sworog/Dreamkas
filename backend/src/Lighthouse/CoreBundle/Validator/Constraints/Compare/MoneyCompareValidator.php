<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Validator\Constraints\DateTime;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\MoneyCompare;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MoneyCompareValidator extends CompareValidator
{
    /**
     * @param $value
     * @return DateTime
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeFieldValue($value)
    {
        if (!$value instanceof Money) {
            throw new UnexpectedTypeException($value, 'Money');
        }
        return $value->getCount();
    }

    /**
     * @param Constraint|MoneyCompare $constraint
     * @param Money $value
     * @return mixed
     */
    protected function formatMessageValue(Constraint $constraint, $value)
    {
        $digits = (int) $constraint->digits;
        $divider = pow(10, $digits);

        return $value->getCount() / $divider;
    }
}
