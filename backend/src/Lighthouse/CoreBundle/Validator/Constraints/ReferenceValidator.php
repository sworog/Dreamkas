<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Document\NullObjectInterface;
use Symfony\Component\Validator\Constraint;

class ReferenceValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|Reference $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof NullObjectInterface) {
            $this->context->addViolation(
                $constraint->message,
                array(),
                $value->getNullObjectIdentifier()
            );
        }
    }
}
