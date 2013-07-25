<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DatesCompare extends NumbersCompare
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.dates_compare.not_valid';

    /**
     * @var string
     */
    public $dateFormat = \DateTime::RFC3339;
}
