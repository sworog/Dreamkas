<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

class ClassMoneyRange extends MoneyRange
{
    /**
     * @var string
     */
    public $field;

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return array('field');
    }

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
 