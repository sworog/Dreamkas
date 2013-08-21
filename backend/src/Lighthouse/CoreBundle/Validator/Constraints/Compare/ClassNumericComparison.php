<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Exception\NullValueException;
use Lighthouse\CoreBundle\Types\Money as MoneyType;
use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ClassNumericComparison extends Comparison
{
    /**
     * @var object
     */
    protected $object;

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @param object $value
     * @param string $field
     * @param Comparator $comparator
     */
    public function __construct($value, $field, Comparator $comparator = null)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->object = $value;
        parent::__construct($field, $comparator);
    }

    /**
     * @param mixed $value
     * @return int
     */
    protected function normalizeValue($value)
    {
        $fieldValue = $this->getObjectValue($value);
        return parent::normalizeValue($fieldValue);
    }

    /**
     * @param string $field
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @throws \Lighthouse\CoreBundle\Exception\NullValueException
     * @return Money|null
     */
    public function getObjectValue($field)
    {
        if (null === $field) {
            throw new NullValueException('field');
        }
        $fieldValue = $this->accessor->getValue($this->object, $field);
        if (null === $fieldValue) {
            throw new NullValueException($field);
        } elseif (!is_numeric($fieldValue)) {
            throw new UnexpectedTypeException($fieldValue, 'numeric');
        } else {
            return $fieldValue;
        }
    }
}
