<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\User;

use Symfony\Component\Validator\Constraint;

class CurrentUserPassword extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.user.password.does_not_match_current';

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
        return 'user_current_password_validator';
    }
}
