<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class Chain extends Constraint
{
    public $constraints = array();

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

        foreach ($this->constraints as $constraint) {
            if (!$constraint instanceof Constraint) {
                throw new UnexpectedTypeException($constraint, 'Constraint');
            }
        }
    }

    /**
     * @return string
     */
    public function getDefaultOption()
    {
        return 'constraints';
    }

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return array('constraints');
    }
}
