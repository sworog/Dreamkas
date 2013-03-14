<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Types\Money as MoneyType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MoneyValidator extends ConstraintValidator
{
    /**
     * @param \Lighthouse\CoreBundle\Types\Money $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($value instanceof MoneyType) {
            $value = $value->getCount();
        }

        $digits = (int) $constraint->digits;

        if ($value <= 0) {
            $this->context->addViolation(
                $constraint->messageNegative,
                array(
                    '{{ value }}' => $value
                )
            );
        }

        if ($value - (int) $value > 0) {
            $this->context->addViolation(
                $constraint->messageDigits,
                array(
                    '{{ value }}' => $value,
                    '{{ digits }}' => $digits
                )
            );
        }
    }
}
