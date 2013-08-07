<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Types\Nullable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator as BaseConstraintValidator;

abstract class ConstraintValidator extends BaseConstraintValidator
{
    /**
     * @param mixed|Nullable $value
     * @return bool
     */
    protected function isNull($value)
    {
        return null === $value || ($value instanceof Nullable && $value->isNull());
    }

    /**
     * @param $value
     * @param Constraint|Constraint[] $constraints
     * @param string $subPath
     * @param null|string|string[] $groups
     * @return bool
     */
    protected function validateValue($value, $constraints, $subPath = '', $groups = null)
    {
        $countViolations = count($this->context->getViolations());
        $this->context->validateValue($value, $constraints, $subPath, $groups);
        if (count($this->context->getViolations()) == $countViolations) {
            return true;
        } else {
            return false;
        }
    }
}
