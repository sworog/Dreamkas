<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

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
