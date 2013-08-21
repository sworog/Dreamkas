<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;

class CallbackValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|\Lighthouse\CoreBundle\Validator\Constraints\Callback $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        // TODO check method can be executed
        call_user_func(array($value, $constraint->method));

        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($constraint->constraints as $field => $constraints) {
            $fieldValue = $accessor->getValue($value, $field);
            $this->context->validateValue($fieldValue, $constraints, $field);
        }
    }
}
