<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

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
     * @return string
     */
    protected function formatMessageValue($value, Constraint $constraint)
    {
        return (string) $value;
    }
}
