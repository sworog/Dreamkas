<?php

namespace Lighthouse\CoreBundle\Command\User;

use JMS\Serializer\Serializer;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.command.user.create_user")
 * @DI\Tag("console.command")
 */
class CreateUser extends Command
{
    /**
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lighthouse:user:create')
            ->setDescription('Create user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addArgument('role', InputArgument::OPTIONAL, 'User role', 'administrator');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Creating user...');

        $user = $this->userProvider->createUser();

        $username = $input->getArgument('username');
        $role = $input->getArgument('role');
        $user->name = $username;
        $user->username = $username;
        $user->role = $role;
        $user->position = $role;

        $this->userProvider->setPassword($user, $input->getArgument('password'));
        $this->userProvider->updateUser($user);

        $output->writeln('Done');

        $output->writeln($this->serializer->serialize($user, 'json'));

        return 0;
    }

    /**
     * @DI\InjectParams({
     *      "userProvider" = @DI\Inject("lighthouse.core.user.provider")
     * })
     * @param UserProvider $userProvider
     */
    public function setUserProvider(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @DI\InjectParams({
     *      "serializer" = @DI\Inject("serializer")
     * })
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }
}
