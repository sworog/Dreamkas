<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PriceValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $digits = (int) $constraint->digits;
        if ($value <= 0) {
            $this->context->addViolation(
                $constraint->messageNegative,
                array(
                    '{{ value }}' => $value
                )
            );
        }
        $compare = $value * pow(10, $digits);
        if ($compare - (int) $compare > 0) {
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
