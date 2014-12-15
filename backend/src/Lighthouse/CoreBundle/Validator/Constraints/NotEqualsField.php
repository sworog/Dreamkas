<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotEqualsField extends Constraint
{
    /**
     * @var string
     */
    public $message = 'lighthouse.validation.errors.not_equals_field';

    /**
     * @var string
     */
    public $field;

    /**
     * {@inheritDoc}
     */
    public function getDefaultOption()
    {
        return 'field';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredOptions()
    {
        return array(
            'field'
        );
    }
}
