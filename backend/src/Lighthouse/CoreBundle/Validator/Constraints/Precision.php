<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Precision extends Constraint
{
    /**
     * @var int
     */
    public $precision = 2;

    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.precision.invalid';

    /**
     * @return string
     */
    public function getDefaultOption()
    {
        return 'precision';
    }
}
