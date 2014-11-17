<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\User;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CurrentUserPassword extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.user.password.does_not_match_current';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'user_current_password_validator';
    }
}
