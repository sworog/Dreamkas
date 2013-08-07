<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ChainValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|Chain $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        foreach ($constraint->constraints as $chainConstraint) {
            if (!$this->validateValue($value, $chainConstraint)) {
                return;
            }
        }
    }
}
