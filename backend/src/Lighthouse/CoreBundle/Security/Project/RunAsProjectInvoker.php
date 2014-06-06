<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Lighthouse\CoreBundle\Document\Project\Project;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.project.run_as_project_invoker")
 */
class RunAsProjectInvoker
{
    /**
     * @var ProjectContext
     */
    protected $projectContext;

    /**
     * @DI\InjectParams({
     *      "projectContext" = @DI\Inject("project.context")
     * })
     * @param ProjectContext $projectContext
     */
    public function __construct(ProjectContext $projectContext)
    {
        $this->projectContext = $projectContext;
    }

    /**
     * @param object $object
     * @param Project $project
     * @return RunAsProjectInvokerProxy
     */
    public function invoke($object, Project $project)
    {
        return new RunAsProjectInvokerProxy($this->projectContext, $project, $object);
    }
}
