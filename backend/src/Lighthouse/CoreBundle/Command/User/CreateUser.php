<?php

namespace Lighthouse\CoreBundle\Command\User;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Project\ProjectRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\SerializerInterface;

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
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * @DI\InjectParams({
     *      "userProvider" = @DI\Inject("lighthouse.core.user.provider"),
     *      "serializer" = @DI\Inject("serializer"),
     *      "projectRepository" = @DI\Inject("lighthouse.core.document.repository.project")
     * })
     * @param UserProvider $userProvider
     * @param SerializerInterface $serializer
     * @param ProjectRepository $projectRepository
     */
    public function __construct(
        UserProvider $userProvider,
        SerializerInterface $serializer,
        ProjectRepository $projectRepository
    ) {
        parent::__construct('lighthouse:user:create');
        $this->userProvider = $userProvider;
        $this->serializer = $serializer;
        $this->projectRepository = $projectRepository;
    }
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Create user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addArgument(
                'roles',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'User roles',
                User::getDefaultRoles()
            )
            ->addOption('project', null, InputOption::VALUE_OPTIONAL, 'Project', true)
            ->addOption('customProjectName', null, InputOption::VALUE_OPTIONAL, 'Custom project name', null);
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

        $email = $input->getArgument('email');
        $roles = $input->getArgument('roles');
        $password =  $input->getArgument('password');

        $user->name = $email;
        $user->email = $email;
        $user->roles = $roles;
        $user->position = reset($roles);

        $projectId = $input->getOption('project');
        $customProjectName = $input->getOption('customProjectName');

        if (true === $projectId) {
            /* @var Project $project */
            $project = $this->projectRepository->createNew();
            if (null !== $customProjectName) {
                $project->name = $customProjectName;
            }
        } elseif (null !== $projectId) {
            $project = $this->projectRepository->find($projectId);
            if (!$project) {
                throw new RuntimeException(sprintf('Project with id#%s not found', $projectId));
            }
        } else {
            $project = null;
        }
        $user->project = $project;

        $this->userProvider->updateUserWithPassword($user, $password, true);

        $output->writeln('Done');

        $output->writeln($this->serializer->serialize($user, 'json'));

        return 0;
    }
}
