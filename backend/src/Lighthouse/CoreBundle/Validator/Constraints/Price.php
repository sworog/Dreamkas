<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Price extends Constraint
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
    public $messageDigits = 'Price should not have more than {{ digits }} digits after dot.';

    /**
     * Error message
     * @var string
     */
    public $messageNegative = 'Price should not be negative.';
}
