<?php

namespace Lighthouse\CoreBundle\Security;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.user.provider")
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @DI\InjectParams({
     *      "userRepository" = @DI\Inject("lighthouse.core.document.repository.user"),
     *      "encoderFactory" = @DI\Inject("security.encoder_factory")
     * })
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, EncoderFactoryInterface $encoderFactory)
    {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
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
        return $class === 'Lighthouse\\CoreBundle\\Document\\User\\User';
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
}
