<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DatesCompareValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|DatesCompare $constraint
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof AbstractDocument) {
            throw new UnexpectedTypeException($value, 'Lighthouse\CoreBundle\Document\AbstractDocument');
        }

        $firstValue = $value->{$constraint->firstField};
        $secondValue = $value->{$constraint->secondField};

        if (null === $secondValue || null === $firstValue) {
            return;
        }

        if (!$secondValue instanceof \DateTime) {
            throw new UnexpectedTypeException($secondValue, '\DateTime');
        }

        if (!$firstValue instanceof \DateTime) {
            throw new UnexpectedTypeException($firstValue, '\DateTime');
        }

        if ($secondValue > $firstValue) {
            $this->context->addViolationAt(
                $constraint->secondField,
                $constraint->message,
                array(
                    '{{ firstValue }}' => $firstValue->format($constraint->dateFormat),
                    '{{ secondValue }}' => $secondValue->format($constraint->dateFormat),
                )
            );
        }
    }
}
