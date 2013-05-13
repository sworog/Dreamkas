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
     * @var int
     */
    public $max = 1000000000;

    /**
     * @var bool
     */
    public $notBlank = false;

    /**
     *
     */
    public $zero = false;

    /**
     * Error message
     * @var string
     */
    public $messageDigits = 'lighthouse.validation.errors.money.digits';

    /**
     * Error message
     * @var string
     */
    public $messageNegative = 'lighthouse.validation.errors.money.negative';

    /**
     * @var string
     */
    public $messageMax = 'lighthouse.validation.errors.money.max';

    /**
     * @var string
     */
    public $messageNotBlank = 'lighthouse.validation.errors.money.not_blank';
}
