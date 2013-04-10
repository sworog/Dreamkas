<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class NotFloat extends Constraint
{
    public $invalidMessage   = 'lighthouse.validation.errors.not_float.invalid';
}