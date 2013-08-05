<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MoneyCompare extends Compare
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.money_compare.not_valid';

    /**
     * @var int
     */
    public $digits = 2;
}
