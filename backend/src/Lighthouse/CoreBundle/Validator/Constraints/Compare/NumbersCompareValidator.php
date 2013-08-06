<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NumbersCompareValidator extends CompareValidator
{
    /**
     * @param $value
     * @return integer|float
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeFieldValue($value)
    {
        if (!is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'numeric');
        }
        return $value;
    }

    /**
     * @param int|float $value
     * @param Constraint|NumbersCompare $constraint
     * @return mixed
     */
    protected function formatMessageValue($value, Constraint $constraint)
    {
        return $value;
    }
}
