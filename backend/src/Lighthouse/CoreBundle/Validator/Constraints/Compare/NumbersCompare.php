<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NumbersCompare extends Compare
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.numbers_compare.not_valid';
}
