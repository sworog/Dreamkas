<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NumbersCompare extends Constraint
{
    /**
     * @var string
     */
    public $minField;

    /**
     * @var string
     */
    public $maxField;

    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.numbers_compare.not_valid';

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return array('minField', 'maxField');
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
