<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Lighthouse\CoreBundle\Document\Project\Project;
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
     * @DI\InjectParams({
     *      "securityContext" = @DI\Inject("security.context")
     * })
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param Project $project
     */
    public function authenticate(Project $project)
    {
        $projectToken = new ProjectToken($project, $this->securityContext->getToken());
        $this->securityContext->setToken($projectToken);
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
