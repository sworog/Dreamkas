<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Types\Money as MoneyType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MoneyValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|Money $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof MoneyType) {
            $value = $value->getCount();
        }

        if (null === $value || '' === $value) {
            if ($constraint->notBlank) {
                $this->context->addViolation(
                    $constraint->messageNotBlank
                );
            }
            return;
        }

        $digits = (int) $constraint->digits;
        $divider = pow(10, $digits);

        if ($value <= 0) {
            $this->context->addViolation(
                $constraint->messageNegative,
                array(
                    '{{ value }}' => $value
                )
            );
        }

        $money = $value / $divider;

        if (null !== $constraint->max && $value > $constraint->max) {
            $this->context->addViolation(
                $constraint->messageMax,
                array(
                    '{{ value }}' => $money,
                    '{{ limit }}' => $constraint->max / $divider,
                )
            );
        }

        if ($value - floor($value) > 0) {
            $this->context->addViolation(
                $constraint->messageDigits,
                array(
                    '{{ value }}' => $money,
                    '{{ digits }}' => $digits
                )
            );
        }
    }
}
