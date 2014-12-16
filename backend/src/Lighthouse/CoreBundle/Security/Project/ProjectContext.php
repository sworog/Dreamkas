<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\ClassNameable;
use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Project\ProjectRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Closure;
use LighthouseKernel;

/**
 * @DI\Service("project.context")
 */
class ProjectContext implements ClassNameable
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @DI\InjectParams({
     *      "tokenStorage" = @DI\Inject("security.token_storage"),
     *      "projectRepository" = @DI\Inject("lighthouse.core.document.repository.project"),
     *      "userProvider" = @DI\Inject("lighthouse.core.user.provider"),
     *      "kernel" = @DI\Inject("kernel")
     * })
     * @param TokenStorageInterface $tokenStorage
     * @param ProjectRepository $projectRepository
     * @param UserProviderInterface $userProvider
     * @param KernelInterface $kernel
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        ProjectRepository $projectRepository,
        UserProviderInterface $userProvider,
        KernelInterface $kernel
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->projectRepository = $projectRepository;
        $this->userProvider  = $userProvider;
        $this->kernel = $kernel;
    }

    /**
     * @param Project $project
     */
    public function authenticate(Project $project)
    {
        $projectToken = new ProjectToken($project, $this->tokenStorage->getToken());
        $this->tokenStorage->setToken($projectToken);
    }

    /**
     * @param string $projectName
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws RuntimeException
     */
    public function authenticateByProjectName($projectName)
    {
        $project = $this->projectRepository->findByName($projectName);
        if (!$project instanceof Project) {
            throw new RuntimeException(sprintf('Project with name "%s" not found', $projectName));
        }
        $this->authenticate($project);
    }

    /**
     * @param User $user
     */
    public function authenticateByUser(User $user)
    {
        $this->authenticate($user->getProject());
    }

    /**
     * @param string $username
     */
    public function authenticateByUsername($username)
    {
        /* @var User $user */
        $user = $this->userProvider->loadUserByUsername($username);
        $this->authenticateByUser($user);
    }

    public function logout()
    {
        $projectToken = $this->tokenStorage->getToken();
        if (!$projectToken instanceof ProjectToken) {
            throw new RuntimeException(
                'Failed to logout. Current token is not ProjectToken: %s',
                get_class($projectToken)
            );
        }
        $this->tokenStorage->setToken($projectToken->getOriginalToken());
    }

    /**
     * @return Project|null
     */
    public function getCurrentProject()
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof ProjectToken) {
            return $token->getProject();
        } elseif ($token instanceof TokenInterface) {
            $user = $token->getUser();
            if ($user instanceof User && $user->getProject()) {
                return $user->getProject();
            }
        }
        return null;
    }

    /**
     * @param callable $callback
     */
    public function applyInProjects(Closure $callback)
    {
        foreach ($this->getAllProjects() as $project) {
            $kernel = new LighthouseKernel($this->kernel->getEnvironment(), $this->kernel->isDebug());
            $kernel->boot();

            $container = $kernel->getContainer();
            $projectContext = $container->get('project.context');
            $projectContext->authenticateByProjectName($project->getName());

            call_user_func($callback, $project, $container);

            $kernel->shutdown();
        }
    }

    /**
     * @return Project[]|Cursor
     */
    public function getAllProjects()
    {
        return $this->projectRepository->findAll();
    }

    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }
}
