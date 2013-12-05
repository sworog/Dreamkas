<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Types\Numeric\Money as MoneyType;
use Symfony\Component\Validator\Constraint;

class MoneyValidator extends ConstraintValidator
{
    /**
     * @param MoneyType $value
     * @param \Symfony\Component\Validator\Constraint|Money $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->isEmpty($value)) {
            if ($constraint->notBlank) {
                $this->context->addViolation(
                    $constraint->messageNotBlank
                );
            }
            return;
        }

        $this->validateNegative($value, $constraint);
        $this->validateMax($value, $constraint);
        $this->validatePrecision($value, $constraint);
    }

    /**
     * @param MoneyType $value
     * @param Money $constraint
     */
    protected function validateNegative(MoneyType $value, Money $constraint)
    {
        if ($value->getCount() < 0 || ($value->getCount() == 0 && $constraint->zero === false)) {
            $this->context->addViolation(
                $constraint->messageNegative,
                array(
                    '{{ value }}' => $value->toString()
                )
            );
        }
    }

    /**
     * @param MoneyType $value
     * @param Money $constraint
     */
    protected function validateMax(MoneyType $value, Money $constraint)
    {
        if (null !== $constraint->max && $value->toNumber() > $constraint->max) {
            $this->context->addViolation(
                $constraint->messageMax,
                array(
                    '{{ value }}' => $value->toString(),
                    '{{ limit }}' => $constraint->max,
                )
            );
        }
    }

    /**
     * @param MoneyType $value
     * @param Money $constraint
     */
    protected function validatePrecision(MoneyType $value, Money $constraint)
    {
        $precisionConstraint = new Precision(
            array(
                'message' => $constraint->messagePrecision,
                'precision' => $value->getPrecision(),
            )
        );

        $this->context->validateValue($value, $precisionConstraint);
    }
}
