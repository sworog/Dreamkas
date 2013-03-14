<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Money extends Constraint
{
    /**
     * Number of digits after dot
     * @var int
     */
    public $digits = 2;

    /**
     * Error message
     * @var string
     */
    public $messageDigits = 'This value should not have more than {{ digits }} digits after dot.';

    /**
     * Error message
     * @var string
     */
    public $messageNegative = 'This value should not be negative.';
}
