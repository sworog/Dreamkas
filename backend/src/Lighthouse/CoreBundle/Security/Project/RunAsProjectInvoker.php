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
     * @var ProjectAuthenticationProvider
     */
    protected $provider;

    /**
     * @DI\InjectParams({
     *      "provider" = @DI\Inject("lighthouse.core.security.project.authentication_provider")
     * })
     * @param ProjectAuthenticationProvider $provider
     */
    public function __construct(ProjectAuthenticationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param object $object
     * @param Project $project
     * @return RunAsProjectInvokerProxy
     */
    public function invoke($object, Project $project)
    {
        return new RunAsProjectInvokerProxy($this->provider, $project, $object);
    }
}
