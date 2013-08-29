<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

class MoneyRange extends Range
{
    /**
     * @var int
     */
    public $precision;

    /**
     * @var string
     */
    public $invalidMessage = 'lighthouse.validation.errors.money_range.not_numeric';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'money_range_validator';
    }
}
