<?php

namespace Lighthouse\CoreBundle\Security\User;

use Hackzilla\PasswordGenerator\Generator\PasswordGenerator;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\ValidatorInterface;
use Swift_Mailer;

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
     * @var ValidatorInterface|ExceptionalValidator
     */
    protected $validator;

    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var PasswordGenerator
     */
    protected $passwordGenerator;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @DI\InjectParams({
     *      "userRepository"    = @DI\Inject("lighthouse.core.document.repository.user"),
     *      "encoderFactory"    = @DI\Inject("security.encoder_factory"),
     *      "validator"         = @DI\Inject("lighthouse.core.validator"),
     *      "mailer"            = @DI\Inject("mailer"),
     *      "container"         = @DI\Inject("service_container"),
     *      "passwordGenerator" = @DI\Inject("hackzilla_password_generator")
     * })
     *
     * @param UserRepository $userRepository
     * @param EncoderFactoryInterface $encoderFactory
     * @param ValidatorInterface $validator
     * @param Swift_Mailer $mailer
     * @param ContainerInterface $container
     * @param PasswordGenerator $passwordGenerator
     */
    public function __construct(
        UserRepository $userRepository,
        EncoderFactoryInterface $encoderFactory,
        ValidatorInterface $validator,
        Swift_Mailer $mailer,
        ContainerInterface $container,
        PasswordGenerator $passwordGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->container = $container;
        $this->passwordGenerator = $passwordGenerator;
    }

    /**
     * @param string $username
     * @return User|UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->findOneBy(array('email' => $username));

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
        return $class === User::getClassName();
    }

    /**
     * @param User $user
     * @param string $password
     */
    public function setPassword(User $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $user->salt = md5(uniqid(true));
        $user->password = $encoder->encodePassword($password, $user->getSalt());
    }

    /**
     * @param UserInterface $user
     */
    public function updateUser(UserInterface $user)
    {
        $this->userRepository->getDocumentManager()->persist($user);
        $this->userRepository->getDocumentManager()->flush();
    }

    /**
     * @param UserInterface|User $user
     * @param string $password
     * @param bool $validate
     */
    public function updateUserWithPassword(UserInterface $user, $password, $validate = false)
    {
        if ($validate) {
            $user->password = $password;
            $this->validator->validate($user);
        }
        $this->setPassword($user, $password);
        $this->updateUser($user);
    }

    /**
     * @return User
     */
    public function createUser()
    {
        return $this->userRepository->createNew();
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string $role
     * @param string $position
     * @return User
     */
    public function createNewUser($email, $password, $name, $role, $position)
    {
        $user = $this->createUser();

        $user->name = $name;
        $user->email = $email;
        $user->roles = array($role);
        $user->position = $position;

        $this->updateUserWithPassword($user, $password, true);

        return $user;
    }

    /**
     * @return string
     */
    public function generateUserPassword()
    {
        return $this->passwordGenerator->generatePassword();
    }

    /**
     * @param User $user
     * @param string $password
     * @return User
     */
    public function sendRegisteredMessage(User $user, $password)
    {
        $messageBody = $this->getMessageBody($password);

        $message = \Swift_Message::newInstance()
            ->setFrom('noreply@lighthouse.pro')
            ->setTo($user->email)
            ->setSubject('Добро пожаловать в Lighthouse')
            ->setBody($messageBody);

        $this->mailer->send($message);

        return $user;
    }

    /**
     * @param string $password
     * @return string
     */
    public function getMessageBody($password)
    {
        $message = $this->container->get('templating')->render(
            'LighthouseCoreBundle:Email:registered.html.php',
            array(
                'password' => $password
            )
        );

        return $message;
    }
}
