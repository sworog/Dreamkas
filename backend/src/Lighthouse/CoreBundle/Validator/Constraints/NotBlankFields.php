<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class NotBlankFields extends Constraint
{
    /**
     * @var array
     */
    public $fields;

    /**
     * @var string
     */
    public $message = 'This value should not be blank.';

    /**
     * @var bool
     */
    public $allowEmpty = true;

    /**
     * @param mixed $options
     * @throws UnexpectedTypeException
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!is_array($this->fields)) {
            throw new UnexpectedTypeException($this->constraints, 'array');
        }
    }

    /**
     * @return string
     */
    public function getDefaultOption()
    {
        return 'fields';
    }

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return array('fields');
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
