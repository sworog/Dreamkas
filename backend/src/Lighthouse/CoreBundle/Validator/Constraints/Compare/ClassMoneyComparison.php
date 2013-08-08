<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Exception\NullValueException;
use Lighthouse\CoreBundle\Types\Money as MoneyType;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ClassMoneyComparison extends MoneyComparison
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
     * @param Money $value
     * @param string $field
     * @param string $limitField
     * @param Comparator $comparator
     */
    public function __construct($value, $field, Comparator $comparator = null)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->object = $value;
        parent::__construct($field, $comparator);
        $this->moneyValue = $this->getObjectValue($field);
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
     * @return Money|null
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function getObjectValue($field)
    {
        if (null === $field) {
            throw new NullValueException('field');
        }
        $fieldValue = $this->accessor->getValue($this->object, $field);
        if (null === $fieldValue) {
            throw new NullValueException($field);
        } elseif (!$fieldValue instanceof MoneyType) {
            throw new UnexpectedTypeException($fieldValue, 'Money');
        } else {
            return $fieldValue;
        }
    }
}
