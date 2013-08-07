<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RangeValidator extends ConstraintValidator
{
    /**
     * @var Comparator
     */
    protected $comparator;

    public function __construct()
    {
        $this->comparator = new Comparator();
    }

    /**
     * @param mixed $value
     * @param Range|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->isEmpty($value)) {
            return;
        }

        try {
            $normalizedValue = $this->normalizeValue($value, $constraint);
        } catch (UnexpectedTypeException $e) {
            $this->context->addViolation(
                $constraint->notNumericMessage,
                array(
                    '{{ value }}' => $value,
                )
            );
            return;
        }

        $operators = array(
            Comparator::GT,
            Comparator::GTE,
            Comparator::LT,
            Comparator::LTE
        );

        foreach ($operators as $operator) {
            if (false === $this->compare($constraint, $operator, $value, $normalizedValue)) {
                return;
            }
        }
    }

    /**
     * @param Range $constraint
     * @param string $operator
     * @param $value
     * @param $normalizedValue
     * @return bool
     */
    protected function compare(Range $constraint, $operator, $value, $normalizedValue)
    {
        $limit = $constraint->getLimit($operator);

        if (!$this->isNull($limit)) {
            $normalizedLimit = $this->normalizeLimit($limit, $constraint, $operator);
            if (!$this->comparator->compare($normalizedValue, $normalizedLimit, $operator)) {
                $this->context->addViolation(
                    $constraint->getMessage($operator),
                    array(
                        '{{ value }}' => $this->formatValueMessage($value, $constraint, $operator),
                        '{{ limit }}' => $this->formatLimitMessage($limit, $constraint, $operator),
                    )
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param $value
     * @param Range $constraint
     * @return int|string
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeValue($value, Range $constraint)
    {
        if (!is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'numeric');
        }
        return $value;
    }

    /**
     * @param $value
     * @param Range $constraint
     * @param $operator
     * @return int|string
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeLimit($value, Range $constraint, $operator)
    {
        if (!is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'numeric');
        }
        return $value;
    }

    /**
     * @param $value
     * @param Range $constraint
     * @param $operator
     * @return mixed
     */
    protected function formatValueMessage($value, Range $constraint, $operator)
    {
        return $value;
    }

    /**
     * @param $limit
     * @param Range $constraint
     * @param $operator
     * @return mixed
     */
    protected function formatLimitMessage($limit, Range $constraint, $operator)
    {
        return $limit;
    }
}
