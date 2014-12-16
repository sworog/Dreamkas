<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class Range extends Constraint
{
    /**
     * @var string
     */
    public $ltMessage           = 'lighthouse.validation.errors.range.lt';

    /**
     * @var string
     */
    public $lteMessage          = 'lighthouse.validation.errors.range.lte';

    /**
     * @var string
     */
    public $gtMessage           = 'lighthouse.validation.errors.range.gt';

    /**
     * @var string
     */
    public $gteMessage          = 'lighthouse.validation.errors.range.gte';

    /**
     * @var string
     */
    public $invalidMessage      = 'lighthouse.validation.errors.range.invalid';

    /**
     * @var mixed
     */
    public $lt;

    /**
     * @var mixed
     */
    public $lte;

    /**
     * @var mixed
     */
    public $gt;

    /**
     * @var mixed
     */
    public $gte;

    /**
     * @var bool
     */
    public $integer = false;

    /**
     * @param array|null $options
     */
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
     * @param string $operator
     * @return mixed
     */
    public function getLimit($operator)
    {
        return $this->{$operator};
    }

    /**
     * @param string $operator
     * @return mixed
     */
    public function getMessage($operator)
    {
        $property = $operator . 'Message';
        return $this->{$property};
    }
}
