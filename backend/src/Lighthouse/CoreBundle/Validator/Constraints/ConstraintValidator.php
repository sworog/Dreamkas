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
     * @param mixed $value
     * @return bool
     */
    protected function isEmpty($value)
    {
        return $this->isNull($value) || '' === $value;
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
        $violations = $this->context->getViolations();
        $countViolations = count($violations);

        $this->context->validateValue($value, $constraints, $subPath, $groups);

        if (count($violations) == $countViolations) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $value
     * @param array|Constraint[] $constraints
     * @param string $subPath
     * @param null|string|string[] $groups
     * @return bool
     */
    protected function chainValidateValue($value, array $constraints, $subPath = '', $groups = null)
    {
        foreach ($constraints as $constraint) {
            if (!$this->validateValue($value, $constraint, $subPath, $groups)) {
                return false;
            }
        }
        return true;
    }
}
