<?php

namespace Lighthouse\CoreBundle\Exception;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedException extends RuntimeException
{
    /**
     * @var ConstraintViolationListInterface
     */
    protected $constraintViolationList;

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     */
    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $this->constraintViolationList = $constraintViolationList;

        $message = $this->createMessage($constraintViolationList);

        parent::__construct($message);
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     * @return string
     */
    protected function createMessage(ConstraintViolationListInterface $constraintViolationList)
    {
        $message = '';
        /* @var ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $message.= $constraintViolation->getPropertyPath() . ': ' . $constraintViolation->getMessage() . "\n";
        }
        return $message;
    }

    /**
     * @return ConstraintViolationListInterface|ConstraintViolationInterface[]
     */
    public function getConstraintViolationList()
    {
        return $this->constraintViolationList;
    }
}
