<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\EmailValidator as BaseEmailValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Email;
use Swift_Validate;

class EmailValidator extends BaseEmailValidator
{
    /**
     * @var ExecutionContextInterface
     */
    protected $context;

    /**
     * @param mixed $value
     * @param Constraint|Email $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $beforeViolationsCount = $this->context->getViolations()->count();

        parent::validate($value, $constraint);

        $afterViolationsCount = $this->context->getViolations()->count();

        if (null === $value || '' === $value || $afterViolationsCount > $beforeViolationsCount) {
            return;
        }

        $this->validateBySwift((string)$value, $constraint);
    }

    /**
     * @param string $email
     * @param Email $constraint
     */
    protected function validateBySwift($email, Email $constraint)
    {
        if (!Swift_Validate::email($email)) {
            $this->context
                ->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($email))
                ->addViolation();
        }
    }
}
