<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Reference extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.reference.not_found';

    /**
     * @return string
     */
    public function getDefaultOption()
    {
        return 'message';
    }
}
