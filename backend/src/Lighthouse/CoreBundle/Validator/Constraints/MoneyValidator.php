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
                $this->context
                    ->buildViolation($constraint->messageNotBlank)
                    ->addViolation()
                ;
            }
        } else {
            $this->validateNegative($value, $constraint);
            $this->validateMax($value, $constraint);
            $this->validatePrecision($value, $constraint);
        }
    }

    /**
     * @param MoneyType $value
     * @param Money $constraint
     */
    protected function validateNegative(MoneyType $value, Money $constraint)
    {
        if ($value->getCount() < 0 || ($value->getCount() == 0 && $constraint->zero === false)) {
            $this->context
                ->buildViolation($constraint->messageNegative)
                    ->setParameter('{{ value }}', $value->toString())
                ->addViolation()
            ;
        }
    }

    /**
     * @param MoneyType $value
     * @param Money $constraint
     */
    protected function validateMax(MoneyType $value, Money $constraint)
    {
        if (null !== $constraint->max && $value->toNumber() > $constraint->max) {
            $this->context
                ->buildViolation($constraint->messageMax)
                    ->setParameter('{{ value }}', $value->toString())
                    ->setParameter('{{ limit }}', $constraint->max)
                ->addViolation()
            ;
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

        $this->validateValue($value, $precisionConstraint);
    }
}
