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
    public $decimals = 2;

    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.precision.decimals';

    /**
     * @return string
     */
    public function getDefaultOption()
    {
        return 'decimals';
    }
}
