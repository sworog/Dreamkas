<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ChainValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|Chain $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        foreach ($constraint->constraints as $chainConstraint) {
            $violations = $this->context->getViolations();
            $violationsCountPre = $violations->count();
            $this->context->validateValue($value, $chainConstraint);
            $violationsCountPost = $violations->count();
            // stop validate chain if new violation was added
            if ($violationsCountPost > $violationsCountPre) {
                return;
            }
        }
    }
}
