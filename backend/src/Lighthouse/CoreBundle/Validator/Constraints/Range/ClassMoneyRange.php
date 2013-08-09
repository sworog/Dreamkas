<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Validator\Constraints\ClassConstraintInterface;

class ClassMoneyRange extends MoneyRange implements ClassConstraintInterface
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

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'class_money_range_validator';
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
}
