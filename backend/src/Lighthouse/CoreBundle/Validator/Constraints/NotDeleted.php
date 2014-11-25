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
    public $isDeletedMessage = 'lighthouse.validation.errors.not_deleted.is_deleted';

    /**
     * @var string
     */
    public $originalIsDeletedMessage = 'lighthouse.validation.errors.not_deleted.original_is_deleted';

    /**
     * @var bool
     */
    public $checkOriginal = false;

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'not_deleted';
    }

    /**
     * @return string
     */
    public function getDefaultOption()
    {
        return 'checkOriginal';
    }

    /**
     * @return string
     */
    /*
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    */
}
