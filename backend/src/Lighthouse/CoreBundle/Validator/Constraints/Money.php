<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Money extends Constraint
{
    /**
     * @var int
     */
    public $max = 10000000;

    /**
     * Money field should not be blank
     * @var bool
     */
    public $notBlank = false;

    /**
     * Money value can be 0
     * @var bool
     */
    public $zero = false;

    /**
     * Error message
     * @var string
     */
    public $messagePrecision = 'lighthouse.validation.errors.money.precision';

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
