<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\User;

use Symfony\Component\Validator\Constraint;

class EmailExists extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.user.email.not_exists';

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'user_email_exists_validator';
    }
}
