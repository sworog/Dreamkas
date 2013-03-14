<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PriceValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $digits = (int) $constraint->digits;
        if ($value->getCount() <= 0) {
            $this->context->addViolation(
                $constraint->messageNegative,
                array(
                    '{{ value }}' => $value->getCount()
                )
            );
        }
        $compare = $value->getCount();
        if ($compare - (int) $compare > 0) {
            $this->context->addViolation(
                $constraint->messageDigits,
                array(
                    '{{ value }}' => $value->getCount(),
                    '{{ digits }}' => $digits
                )
            );
        }
    }
}
