<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotFloat extends Constraint
{
    /**
     * @var string
     */
    public $invalidMessage = 'lighthouse.validation.errors.not_float.invalid';
}
