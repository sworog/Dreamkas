<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Validator\Constraints\Range\Range;

class MoneyRange extends Range
{
    /**
     * @var int
     */
    public $digits;

    /**
     * @var string
     */
    public $invalidValue = 'lighthouse.validation.errors.money_range.not_numeric';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'money_range_validator';
    }
}
