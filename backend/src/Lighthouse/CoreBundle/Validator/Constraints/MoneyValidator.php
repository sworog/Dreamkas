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

        $count = $value->getCount();

        $precision = (int) $constraint->precision;
        $divider = pow(10, $precision);

        if ($count < 0 || ($count == 0 && $constraint->zero === false)) {
            $this->context->addViolation(
                $constraint->messageNegative,
                array(
                    '{{ value }}' => $count
                )
            );
        }

        $money = $count / $divider;

        if (null !== $constraint->max && $count > $constraint->max) {
            $this->context->addViolation(
                $constraint->messageMax,
                array(
                    '{{ value }}' => $money,
                    '{{ limit }}' => $constraint->max / $divider,
                )
            );
        }

        $this->validatePrecision($value, $constraint);
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
