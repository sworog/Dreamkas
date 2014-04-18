<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Types\RawValue;
use Symfony\Component\Validator\Constraint;

class PrecisionValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Precision|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof RawValue) {
            $value = $value->getRaw();
        }

        if ($this->isEmpty($value)) {
            return;
        }

        $floatingPart = $this->getFloatingPart($value);
        if (strlen($floatingPart) > $constraint->precision) {
            $this->context->addViolation(
                $constraint->message,
                array(
                    '{{ value }}' => $value,
                    '{{ precision }}' => $constraint->precision
                )
            );
        }
    }

    /**
     * @param string $value
     * @return string
     */
    protected function getFloatingPart($value)
    {
        $parts = explode('.', $value);
        $floatingPart = (isset($parts[1])) ? $parts[1] : '';
        $floatingPart = rtrim($floatingPart, '0');
        return $floatingPart;
    }
}
