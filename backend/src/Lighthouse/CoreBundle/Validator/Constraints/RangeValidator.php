<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

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

        try {
            $normalizedValue = $this->normalizeValue($value, $constraint);
        } catch (UnexpectedTypeException $e) {
            $this->context->addViolation(
                $constraint->notNumericMessage,
                array(
                    '{{ value }}' => $value,
                )
            );
            return;
        }

        if (null !== $constraint->gt && $normalizedValue <= $constraint->gt) {
            $this->context->addViolation(
                $constraint->gtMessage,
                array(
                    '{{ value }}' => $this->formatMessageValue($value, $constraint),
                    '{{ limit }}' => $constraint->gt,
                )
            );
            return;
        } elseif (null !== $constraint->gte && $normalizedValue < $constraint->gte) {
            $this->context->addViolation(
                $constraint->gteMessage,
                array(
                    '{{ value }}' => $this->formatMessageValue($value, $constraint),
                    '{{ limit }}' => $constraint->gte,
                )
            );
            return;
        }

        if (null !== $constraint->lt && $normalizedValue >= $constraint->lt) {
            $this->context->addViolation(
                $constraint->ltMessage,
                array(
                    '{{ value }}' => $this->formatMessageValue($value, $constraint),
                    '{{ limit }}' => $constraint->lt,
                )
            );
            return;
        } elseif (null !== $constraint->lte && $normalizedValue > $constraint->lte) {
            $this->context->addViolation(
                $constraint->lteMessage,
                array(
                    '{{ value }}' => $this->formatMessageValue($value, $constraint),
                    '{{ limit }}' => $constraint->lte,
                )
            );
            return;
        }
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return int|string
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeValue($value, Constraint $constraint)
    {
        if (!is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'numeric');
        }
        return $value;
    }

    /**
     * @param int|float $value
     * @param Constraint $constraint
     * @return string
     */
    protected function formatMessageValue($value, Constraint $constraint)
    {
        return $value;
    }
}
