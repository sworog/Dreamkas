<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraint;

class NotEqualsFieldValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|NotEqualsField $constraint
     * @return bool
     */
    public function validate($value, Constraint $constraint)
    {
        $root = $this->context->getRoot();
        if ($root instanceof AbstractDocument) {
            $fieldValue = $root->{$constraint->field};
        } else {
            $fieldValue = $root->get($constraint->field)->getData();
        }

        if ($value == $fieldValue) {
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
