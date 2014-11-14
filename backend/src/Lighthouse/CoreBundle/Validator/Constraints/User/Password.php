<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\User;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Password extends Constraint
{
    /**
     * @var int
     */
    public $minLength = 6;

    /**
     * @var string
     */
    public $equalsEmailMessage = 'lighthouse.validation.errors.user.password.not_equals_email';
}
