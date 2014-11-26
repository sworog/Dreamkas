<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotDeleted extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.not_deleted.is_deleted';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'not_deleted';
    }
}
