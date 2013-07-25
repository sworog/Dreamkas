<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NumbersCompareValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|NumbersCompare $constraint
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof AbstractDocument) {
            throw new UnexpectedTypeException($value, 'Lighthouse\\CoreBundle\\Document\\AbstractDocument');
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $minFieldValue = $accessor->getValue($value, $constraint->minField);
        $maxFieldValue = $accessor->getValue($value, $constraint->maxField);

        if (null === $maxFieldValue || null === $minFieldValue) {
            return;
        }

        $this->validateFieldValue($minFieldValue);
        $this->validateFieldValue($maxFieldValue);

        if ($minFieldValue > $maxFieldValue) {
            $this->context->addViolationAt(
                $constraint->minField,
                $constraint->message,
                array(
                    '{{ firstValue }}' => $this->formatValue($constraint, $minFieldValue),
                    '{{ secondValue }}' => $this->formatValue($constraint, $maxFieldValue),
                )
            );
        }
    }

    /**
     * @param $value
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function validateFieldValue($value)
    {
        if (!is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'numeric');
        }
    }

    /**
     * @param Constraint $constraint
     * @param $value
     * @return mixed
     */
    protected function formatValue(Constraint $constraint, $value)
    {
        return $value;
    }
}
