<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
abstract class Compare extends Constraint
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
    public $message = 'lighthouse.validation.errors.compare';

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
