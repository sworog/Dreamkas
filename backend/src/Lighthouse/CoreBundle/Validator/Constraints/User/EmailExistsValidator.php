<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\User;

use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Lighthouse\CoreBundle\Validator\Constraints\User\EmailExists;
use Symfony\Component\Validator\Constraint;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.validator.user.email_exists")
 * @DI\Tag("validator.constraint_validator", attributes={"alias"="user_email_exists_validator"})
 */
class EmailExistsValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @DI\InjectParams({
     *      "userRepository" = @DI\Inject("lighthouse.core.document.repository.user")
     * })
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint|EmailExists $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->isEmpty($value)) {
            return;
        }

        $user = $this->userRepository->findOneByEmail($value);
        if (!$user) {
            $this->context->addViolation(
                $constraint->message,
                array(
                    '{{ email }}' => $value,
                ),
                $value
            );
        }
    }
}
