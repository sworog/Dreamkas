<?php

namespace Lighthouse\CoreBundle\Validator;

use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.validator", parent="validator")
 */
class ExceptionalValidator extends Validator
{
    /**
     * @param mixed $value
     * @param null $groups
     * @param bool $traverse
     * @param bool $deep
     * @return ConstraintViolationList|ConstraintViolationListInterface
     */
    public function validate($value, $groups = null, $traverse = false, $deep = false)
    {
        $constraintViolationList = parent::validate($value, $groups, $traverse, $deep);
        return $this->processConstraintViolationList($constraintViolationList);
    }

    /**
     * @param mixed $containingValue
     * @param string $property
     * @param null $groups
     * @return ConstraintViolationList|ConstraintViolationListInterface
     */
    public function validateProperty($containingValue, $property, $groups = null)
    {
        $constraintViolationList = parent::validateProperty($containingValue, $property, $groups);
        return $this->processConstraintViolationList($constraintViolationList);
    }

    /**
     * @param string $containingValue
     * @param string $property
     * @param string $value
     * @param null $groups
     * @return ConstraintViolationList|ConstraintViolationListInterface
     */
    public function validatePropertyValue($containingValue, $property, $value, $groups = null)
    {
        $constraintViolationList = parent::validatePropertyValue(
            $containingValue,
            $property,
            $value,
            $groups
        );
        return $this->processConstraintViolationList($constraintViolationList);
    }

    /**
     * @param mixed $value
     * @param Constraint|Constraint[] $constraints
     * @param null $groups
     * @return ConstraintViolationListInterface
     */
    public function validateValue($value, $constraints, $groups = null)
    {
        $constraintViolationList = parent::validateValue($value, $constraints, $groups);
        return $this->processConstraintViolationList($constraintViolationList);
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     * @return ConstraintViolationListInterface
     * @throws ValidationFailedException
     */
    protected function processConstraintViolationList(ConstraintViolationListInterface $constraintViolationList)
    {
        if (count($constraintViolationList) > 0) {
            throw new ValidationFailedException($constraintViolationList);
        } else {
            return $constraintViolationList;
        }
    }
}
