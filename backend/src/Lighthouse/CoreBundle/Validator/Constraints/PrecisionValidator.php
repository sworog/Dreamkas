<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class PrecisionValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Precision|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->isEmpty($value)) {
            return;
        }

        $rounded = $value * pow(10, $constraint->decimals);
        $rounded = (float) (string) $rounded;

        if ($rounded - floor($rounded) > 0) {
            $this->context->addViolation(
                $constraint->message,
                array(
                    '{{ value }}' => $value,
                    '{{ decimals }}' => $constraint->decimals
                )
            );
        }
    }
}
