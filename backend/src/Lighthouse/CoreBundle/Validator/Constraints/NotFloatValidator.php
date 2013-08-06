<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class NotFloatValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|NotFloat $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if ((string) (int) $value !== (string) $value) {
            $this->context->addViolation(
                $constraint->invalidMessage,
                array(
                    '{{ value }}' => $value,
                )
            );
        }
    }
}
