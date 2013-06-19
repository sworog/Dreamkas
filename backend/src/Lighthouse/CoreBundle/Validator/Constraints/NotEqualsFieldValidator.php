<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class NotEqualsFieldValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return bool|void
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value == $this->context->getRoot()->get($constraint->field)->getData()) {
            $this->context->addViolation(
                $constraint->message,
                array(
                    '{{ field }}' => $constraint->field,
                )
            );

            return false;
        }

        return true;
    }
}
