<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\ClassNameable;
use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Project\ProjectRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Closure;

/**
 * @DI\Service("project.context")
 */
class ProjectContext implements ClassNameable
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

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
     *      "securityContext" = @DI\Inject("security.context"),
     *      "projectRepository" = @DI\Inject("lighthouse.core.document.repository.project"),
     *      "userProvider" = @DI\Inject("lighthouse.core.user.provider"),
     *      "kernel" = @DI\Inject("kernel")
     * })
     * @param SecurityContextInterface $securityContext
     * @param ProjectRepository $projectRepository
     * @param UserProviderInterface $userProvider
     * @param KernelInterface $kernel
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        ProjectRepository $projectRepository,
        UserProviderInterface $userProvider,
        KernelInterface $kernel
    ) {
        $this->securityContext = $securityContext;
        $this->projectRepository = $projectRepository;
        $this->userProvider  = $userProvider;
        $this->kernel = $kernel;
    }

    /**
     * @param Project $project
     */
    public function authenticate(Project $project)
    {
        $projectToken = new ProjectToken($project, $this->securityContext->getToken());
        $this->securityContext->setToken($projectToken);
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
        $projectToken = $this->securityContext->getToken();
        if (!$projectToken instanceof ProjectToken) {
            throw new RuntimeException(
                'Failed to logout. Current token is not ProjectToken: %s',
                get_class($projectToken)
            );
        }
        $this->securityContext->setToken($projectToken->getOriginalToken());
    }

    /**
     * @return Project|null
     */
    public function getCurrentProject()
    {
        $token = $this->securityContext->getToken();
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
            $this->authenticate($project);
            $kernel = new \LighthouseKernel(
                $this->kernel->getEnvironment(),
                $this->kernel->isDebug()
            );
            $kernel->boot();
            $container = $kernel->getContainer();
            $container->get('project.context')->authenticateByProjectName($project->getName());
            call_user_func($callback, $project, $kernel->getContainer());
            $kernel->shutdown();
            $this->logout();
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
