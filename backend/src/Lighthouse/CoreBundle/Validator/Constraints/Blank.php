<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Blank extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.core.validation.errors.blank';
}
