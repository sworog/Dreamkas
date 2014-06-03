<?php

namespace Lighthouse\CoreBundle\Validator;

use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception;
use Symfony\Component\Validator\MetadataInterface;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @DI\Service("lighthouse.core.validator")
 */
class ExceptionalValidator implements ValidatorInterface
{
    /**
     * @var ValidatorInterface
     */
    protected $delegate;

    /**
     * @DI\InjectParams({
     *      "delegate" = @DI\Inject("validator")
     * })
     * @param ValidatorInterface $delegate
     */
    public function __construct(ValidatorInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param mixed $value
     * @param Constraint|Constraint[] $constraints
     * @param array|null $groups
     * @return ConstraintViolationListInterface
     */
    public function validate($value, $constraints = null, $groups = null)
    {
        $constraintViolationList = $this->delegate->validate($value, $constraints, $groups);
        return $this->processConstraintViolationList($constraintViolationList);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param null $groups
     * @return ConstraintViolationList|ConstraintViolationListInterface
     */
    public function validateProperty($object, $propertyName, $groups = null)
    {
        $constraintViolationList = $this->delegate->validateProperty($object, $propertyName, $groups);
        return $this->processConstraintViolationList($constraintViolationList);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param string $value
     * @param array|null $groups
     * @return ConstraintViolationList|ConstraintViolationListInterface
     */
    public function validatePropertyValue($object, $propertyName, $value, $groups = null)
    {
        $constraintViolationList = $this->delegate->validatePropertyValue(
            $object,
            $propertyName,
            $value,
            $groups
        );
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

    /**
     * @param mixed $value
     * @return MetadataInterface
     */
    public function getMetadataFor($value)
    {
        return $this->delegate->getMetadataFor($value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function hasMetadataFor($value)
    {
        return $this->delegate->hasMetadataFor($value);
    }

    /**
     * @return ContextualValidatorInterface
     */
    public function startContext()
    {
        return $this->delegate->startContext();
    }

    /**
     * @param ExecutionContextInterface $context
     * @return ContextualValidatorInterface
     */
    public function inContext(ExecutionContextInterface $context)
    {
        return $this->delegate->inContext($context);
    }
}
