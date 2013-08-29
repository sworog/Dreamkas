<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class BlankValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|Blank $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$this->isNull($value)) {
            $this->context->addViolation(
                $constraint->message,
                array('{{ value }}' => $value)
            );
        }
    }
}
