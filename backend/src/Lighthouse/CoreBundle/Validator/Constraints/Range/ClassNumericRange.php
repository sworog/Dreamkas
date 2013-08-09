<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Validator\Constraints\ClassConstraintInterface;

class ClassNumericRange extends Range implements ClassConstraintInterface
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
    public function getField()
    {
        return $this->field;
    }
}
