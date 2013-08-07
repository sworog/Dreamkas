<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class Range extends Constraint
{
    public $ltMessage           = 'lighthouse.validation.errors.range.lt';
    public $lteMessage          = 'lighthouse.validation.errors.range.lte';
    public $gtMessage           = 'lighthouse.validation.errors.range.gt';
    public $gteMessage          = 'lighthouse.validation.errors.range.gte';
    public $notNumericMessage   = 'lighthouse.validation.errors.range.not_numeric';

    public $lt;
    public $lte;
    public $gt;
    public $gte;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->lt
            && null === $this->lte
            && null === $this->gt
            && null === $this->gte
        ) {
            throw new MissingOptionsException(
                'Either option "gt", "gte", "lt" or "lte" must be given for constraint ' . __CLASS__,
                array('lt', 'lte', 'gt', 'gte')
            );
        }
    }

    /**
     * @param stirng $operator
     * @return mixed
     */
    public function getLimit($operator)
    {
        return $this->$operator;
    }

    /**
     * @param string $operator
     * @return mixed
     */
    public function getMessage($operator)
    {
        $property = $operator . 'Message';
        return $this->$property;
    }
}
