<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DatesCompareValidator extends NumbersCompareValidator
{
    /**
     * @param $value
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function validateFieldValue($value)
    {
        if (!$value instanceof \DateTime) {
            throw new UnexpectedTypeException($value, '\DateTime');
        }
    }

    /**
     * @param Constraint $constraint
     * @param $value
     * @return mixed
     */
    protected function formatValue(Constraint $constraint, $value)
    {
        return $value->format($constraint->dateFormat);
    }
}
