<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\User;

use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Lighthouse\CoreBundle\Validator\Constraints\NotEqualsField;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;

class PasswordValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param Constraint|Password $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->isEmpty($value)) {
            return;
        }

        $constraints = array(
            new Length(
                array(
                    'min' => $constraint->minLength,
                )
            ),
            new NotEqualsField(
                array(
                    'field' => 'email',
                    'message' => $constraint->equalsEmailMessage
                )
            )
        );

        $this->context
            ->getValidator()
                ->inContext($this->context)
                ->validate($value, $constraints, $constraint->groups);
    }
}
