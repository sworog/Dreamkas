<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Document\AbstractDocument;
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
