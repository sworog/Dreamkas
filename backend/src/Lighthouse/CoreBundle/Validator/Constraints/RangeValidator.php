<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RangeValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Range|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || "" === $value) {
            return;
        }

        if (!is_numeric($value)) {
            $this->context->addViolation(
                $constraint->notNumericMessage,
                array(
                    '{{ value }}' => $value,
                )
            );
            return;
        }

        if (null !== $constraint->gt && $value <= $constraint->gt) {
            $this->context->addViolation(
                $constraint->gtMessage,
                array(
                    '{{ value }}' => $value,
                    '{{ limit }}' => $constraint->gt,
                )
            );
            return;
        } elseif (null !== $constraint->gte && $value < $constraint->gte) {
            $this->context->addViolation(
                $constraint->gteMessage,
                array(
                    '{{ value }}' => $value,
                    '{{ limit }}' => $constraint->gte,
                )
            );
            return;
        }

        if (null !== $constraint->lt && $value >= $constraint->lt) {
            $this->context->addViolation(
                $constraint->ltMessage,
                array(
                    '{{ value }}' => $value,
                    '{{ limit }}' => $constraint->lt,
                )
            );
            return;
        } elseif (null !== $constraint->lte && $value > $constraint->lte) {
            $this->context->addViolation(
                $constraint->lteMessage,
                array(
                    '{{ value }}' => $value,
                    '{{ limit }}' => $constraint->lte,
                )
            );
            return;
        }
    }
}
