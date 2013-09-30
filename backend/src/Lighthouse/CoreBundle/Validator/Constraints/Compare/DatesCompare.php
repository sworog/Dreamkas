<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use DateTime;

/**
 * @Annotation
 */
class DatesCompare extends Compare
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.dates_compare.not_valid';

    /**
     * @var string
     */
    public $dateFormat = DateTime::RFC3339;
}
