<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

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
    public $precision = 2;
}
