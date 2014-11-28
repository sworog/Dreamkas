<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\User;

use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.validator.user.current_user_password")
 * @DI\Tag("validator.constraint_validator", attributes={"alias"="user_current_password_validator"})
 */
class CurrentUserPasswordValidator extends ConstraintValidator
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @DI\InjectParams({
     *      "encoderFactory" = @DI\Inject("security.encoder_factory"),
     *      "securityContext" = @DI\Inject("security.context"),
     * })
     * @param EncoderFactoryInterface $encoderFactory
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, SecurityContextInterface $securityContext)
    {
        $this->encoderFactory = $encoderFactory;
        $this->securityContext = $securityContext;
    }

    /**
     * @param string $value The value that should be validated
     * @param Constraint|CurrentUserPassword $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var UserInterface $user */
        $user = $this->securityContext->getToken()->getUser();
        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($value, $user->getSalt());
        if ($encodedPassword !== $user->getPassword()) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
