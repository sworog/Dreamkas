<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class Callback extends Constraint
{
    /**
     * Method that should be called in document before validation
     * @var string
     */
    public $method;

    /**
     * @var array array(fieldName => Constraints[])
     */
    public $constraints;


    /**
     * @param mixed $options
     * @throws UnexpectedTypeException
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!is_array($this->constraints)) {
            throw new UnexpectedTypeException($this->constraints, 'array');
        }

        foreach ($this->constraints as $constraints) {
            if (is_array($constraints)) {
                foreach ($constraints as $constraint) {
                    if (!$constraint instanceof Constraint) {
                        throw new UnexpectedTypeException($constraints, 'Constraint');
                    }
                }
            } elseif (!$constraints instanceof Constraint) {
                throw new UnexpectedTypeException($constraints, 'Constraint');
            }
        }
    }

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return array('method', 'constraints');
    }

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
