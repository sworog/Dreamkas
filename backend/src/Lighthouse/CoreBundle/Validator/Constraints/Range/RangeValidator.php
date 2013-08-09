<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Lighthouse\CoreBundle\Exception\NullValueException;
use Lighthouse\CoreBundle\Validator\Constraints\ClassConstraintInterface;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\Comparator;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\Comparison;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Lighthouse\CoreBundle\Validator\Constraints\numeric;
use Lighthouse\CoreBundle\Validator\Constraints\Range\Range;
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
        try {
            $comparison = $this->createComparison($value, $constraint);
        } catch (NullValueException $e) {
            return;
        } catch (UnexpectedTypeException $e) {
            $this->addViolation($constraint, $constraint->invalidValue);
            return;
        }

        $operators = array(
            Comparator::GT,
            Comparator::GTE,
            Comparator::LT,
            Comparator::LTE
        );

        foreach ($operators as $operator) {
            if (false === $this->compare($constraint, $operator, $comparison)) {
                return;
            }
        }
    }

    /**
     * @param numeric $value
     * @param Range $constraint
     * @return Comparison
     */
    protected function createComparison($value, Range $constraint)
    {
        return new Comparison($value, $this->comparator);
    }

    /**
     * @param Range $constraint
     * @param string $operator
     * @param Comparison $comparison
     * @return bool
     * @throws \Exception|\Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function compare(Range $constraint, $operator, Comparison $comparison)
    {
        $limit = $constraint->getLimit($operator);

        try {
            if (!$comparison->compare($limit, $operator)) {
                $this->addViolation(
                    $constraint,
                    $constraint->getMessage($operator),
                    $params = array(
                        '{{ value }}' => $this->formatValueMessage($comparison, $constraint, $operator),
                        '{{ limit }}' => $this->formatLimitMessage($limit, $constraint, $operator, $comparison),
                    )
                );
                return false;
            }
        } catch (UnexpectedTypeException $e) {
            throw $e;
        } catch (NullValueException $e) {

        }
        return true;
    }

    /**
     * @param numeric $value
     * @param Range $constraint
     * @param string $operator
     * @return string
     */
    protected function formatValueMessage(Comparison $comparison, Range $constraint, $operator)
    {
        return (string) $comparison->getValue();
    }

    /**
     * @param numeric $limit
     * @param Range $constraint
     * @param string $operator
     * @return string
     */
    protected function formatLimitMessage($limit, Range $constraint, $operator, Comparison $comparison)
    {
        return (string) $limit;
    }

    /**
     * @param Range $constraint
     * @param $message
     * @param array $params
     * @param null $invalidValue
     * @param null $pluralization
     * @param null $code
     */
    protected function addViolation(
        Range $constraint,
        $message,
        array $params = array(),
        $invalidValue = null,
        $pluralization = null,
        $code = null
    ) {
        if ($constraint instanceof ClassConstraintInterface) {
            $this->context->addViolationAt(
                $constraint->getField(),
                $message,
                $params,
                $invalidValue,
                $pluralization,
                $code
            );
        } else {
            $this->context->addViolation(
                $message,
                $params,
                $invalidValue,
                $pluralization,
                $code
            );
        }
    }
}
