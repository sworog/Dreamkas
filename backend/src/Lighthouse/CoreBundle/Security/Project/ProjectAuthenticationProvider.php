<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Document\Project\ProjectRepository;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.security.project.authentication_provider")
 */
class ProjectAuthenticationProvider
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
     * @DI\InjectParams({
     *      "securityContext" = @DI\Inject("security.context"),
     *      "projectRepository" = @DI\Inject("lighthouse.core.document.repository.project")
     *
     * })
     * @param SecurityContextInterface $securityContext
     * @param ProjectRepository $projectRepository
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        ProjectRepository $projectRepository
    ) {
        $this->securityContext = $securityContext;
        $this->projectRepository = $projectRepository;
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
}
