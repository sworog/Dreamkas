<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

class MoneyRange extends Range
{
    /**
     * @var int
     */
    public $digits;

    /**
     * @var string
     */
    public $notNumericMessage = 'lighthouse.validation.errors.money_range.not_numeric';


    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'money_range_validator';
    }
}
