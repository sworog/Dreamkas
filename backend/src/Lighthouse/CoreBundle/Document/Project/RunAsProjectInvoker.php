<?php

namespace Lighthouse\CoreBundle\Document\Project;

use Symfony\Component\Security\Core\SecurityContextInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.project.run_as_project_invoker")
 */
class RunAsProjectInvoker
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
     * @param $object
     * @param Project $project
     * @return RunAsProjectInvokerProxy
     */
    public function invoke($object, Project $project)
    {
        return new RunAsProjectInvokerProxy($this->securityContext, $project, $object);
    }
}
