<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DatesCompare extends Constraint
{
    /**
     * @var string
     */
    public $firstField;

    /**
     * @var string
     */
    public $secondField;

    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.dates_compare.not_valid';

    /**
     * @var string
     */
    public $dateFormat = \DateTime::RFC3339;

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return array('firstField', 'secondField');
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
