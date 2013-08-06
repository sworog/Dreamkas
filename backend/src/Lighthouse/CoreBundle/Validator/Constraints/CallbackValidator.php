<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CallbackValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|Callback $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        // TODO check method can be executed
        call_user_func(array($value, $constraint->method));

        foreach ($constraint->constraints as $field => $constraints) {
            // TODO check entity has field
            $fieldValue = $value->$field;
            $this->context->validateValue($fieldValue, $constraints, $field);
        }
    }
}
