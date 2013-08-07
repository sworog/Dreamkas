<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Lighthouse\CoreBundle\Types\Money as MoneyType;

class ClassMoneyRangeValidator extends MoneyRangeValidator
{
    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @param MoneyModelTransformer $moneyTransformer
     */
    public function __construct(MoneyModelTransformer $moneyTransformer)
    {
        parent::__construct($moneyTransformer);

        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param object $value
     * @param Constraint|ClassMoneyRange $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $fieldValue = $this->getFieldValue($value, $constraint->field);
        if ($this->isNull($fieldValue)) {
            return;
        }

        return parent::validate($value, $constraint);
    }


    /**
     * @param object $value
     * @param Range|ClassMoneyRange $constraint
     * @return int|string
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeValue($value, Range $constraint)
    {
        $fieldValue = $this->getFieldValue($value, $constraint->field);

        return parent::normalizeValue($fieldValue, $constraint);
    }

    /**
     * @param object $value
     * @param Constraint|MoneyRange $constraint
     * @return string|void
     */
    protected function formatValueMessage($value, Constraint $constraint)
    {
        $fieldValue = $this->getFieldValue($value, $constraint->field);

        return parent::formatValueMessage($fieldValue, $constraint);
    }

    /**
     * @param string $limit
     * @param Range $constraint
     * @param $operator
     * @param $value
     * @return int|Money|null|string
     */
    protected function normalizeLimit($limit, Range $constraint, $operator, $value)
    {
        $limitValue = $this->getFieldValue($value, $limit);
        if ($limitValue instanceof MoneyType) {
            return $limitValue->getCount();
        } else {
            return $limitValue;
        }
    }

    /**
     * @param $limit
     * @param Range|ClassMoneyRange $constraint
     * @param $operator
     * @param $value
     * @return int|mixed
     */
    protected function formatLimitMessage($limit, Range $constraint, $operator, $value)
    {
        $limitValue = $this->getFieldValue($value, $limit);
        return $this->transformer->transform($limitValue, $constraint->digits);
    }

    /**
     * @param $value
     * @param $field
     * @return Money|null
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function getFieldValue($value, $field)
    {
        $fieldValue = $this->accessor->getValue($value, $field);
        if (null === $fieldValue) {
            return null;
        } elseif (!$fieldValue instanceof MoneyType) {
            throw new UnexpectedTypeException($fieldValue, 'Money');
        } else {
            return $fieldValue;
        }
    }
}
