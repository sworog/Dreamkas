<?php

namespace Lighthouse\CoreBundle\Security\User;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * @DI\Service("lighthouse.core.user.provider")
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var string
     */
    protected $class = 'Lighthouse\\CoreBundle\\Document\\User\\User';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @DI\InjectParams({
     *      "userRepository" = @DI\Inject("lighthouse.core.document.repository.user"),
     *      "encoderFactory" = @DI\Inject("security.encoder_factory"),
     *      "validator"      = @DI\Inject("validator")
     * })
     *
     * @param UserRepository $userRepository
     * @param EncoderFactoryInterface $encoderFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(
        UserRepository $userRepository,
        EncoderFactoryInterface $encoderFactory,
        ValidatorInterface $validator
    ) {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->validator = $validator;
    }

    /**
     * @param string $username
     * @return User|UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->findOneBy(array('username' => $username));

        if (!$user) {
            $e = new UsernameNotFoundException();
            $e->setUsername($username);
            throw $e;
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return User|UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf("Invalid user class %s", get_class($user)));
        }

        $refreshedUser = $this->userRepository->find($user->id);

        if (!$refreshedUser) {
            throw new UsernameNotFoundException(sprintf("User with id %s not found", $user->id));
        }

        return $refreshedUser;
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class === $this->class;
    }

    /**
     * @param User $user
     * @param string $password
     */
    public function setPassword(User $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $user->salt = md5(date('cr'));
        $user->password = $encoder->encodePassword($password, $user->getSalt());
    }

    /**
     * @param UserInterface $user
     * @param bool $validate
     */
    public function updateUser(UserInterface $user, $validate = false)
    {
        if ($validate) {
            $this->validate($user);
        }

        $this->userRepository->getDocumentManager()->persist($user);
        $this->userRepository->getDocumentManager()->flush();
    }

    /**
     * @param UserInterface $user
     * @throws ValidationFailedException
     */
    protected function validate(UserInterface $user)
    {
        $constraintViolationList = $this->validator->validate($user);
        if ($constraintViolationList->count() > 0) {
            throw new ValidationFailedException($constraintViolationList);
        }
    }

    /**
     * @param UserInterface $user
     * @param string $password
     * @param bool $validate
     */
    public function updateUserWithPassword(UserInterface $user, $password, $validate = false)
    {
        $this->setPassword($user, $password);
        $this->updateUser($user, $validate);
    }

    /**
     * @return \Lighthouse\CoreBundle\Document\User\User
     */
    public function createUser()
    {
        return $this->userRepository->createNew();
    }
}
