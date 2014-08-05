<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Project\ProjectRepository;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @DI\Service("project.context")
 */
class ProjectContext
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
     * @DI\InjectParams({
     *      "securityContext" = @DI\Inject("security.context"),
     *      "projectRepository" = @DI\Inject("lighthouse.core.document.repository.project"),
     *      "userProvider" = @DI\Inject("lighthouse.core.user.provider")
     * })
     * @param SecurityContextInterface $securityContext
     * @param ProjectRepository $projectRepository
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        ProjectRepository $projectRepository,
        UserProviderInterface $userProvider
    ) {
        $this->securityContext = $securityContext;
        $this->projectRepository = $projectRepository;
        $this->userProvider  = $userProvider;
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
}
