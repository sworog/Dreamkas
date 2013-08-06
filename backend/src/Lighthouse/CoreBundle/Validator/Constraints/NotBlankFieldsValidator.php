<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;

class NotBlankFieldsValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|NotBlankFields $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $blankFields = array();

        foreach ($constraint->fields as $field) {
            $fieldValue = $accessor->getValue($value, $field);
            if ($this->isNull($fieldValue)) {
                $blankFields[] = $field;
            }
        }

        $fieldsCount = count($constraint->fields);
        $blankFieldsCount = count($blankFields);

        // All fields null skip validation
        if ($fieldsCount == $blankFieldsCount && $constraint->allowEmpty) {
            return;
        }

        if ($blankFieldsCount > 0) {
            foreach ($blankFields as $blankField) {
                $this->context->addViolationAt($blankField, $constraint->message);
            }
        }
    }
}
