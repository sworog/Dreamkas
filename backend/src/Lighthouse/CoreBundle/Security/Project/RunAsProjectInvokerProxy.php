<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Lighthouse\CoreBundle\Document\Project\Project;

class RunAsProjectInvokerProxy
{
    /**
     * @var ProjectContext
     */
    protected $projectContext;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var object
     */
    protected $object;

    /**
     * @param ProjectContext $projectContext
     * @param Project $project
     * @param $object
     */
    public function __construct(ProjectContext $projectContext, Project $project, $object)
    {
        $this->projectContext = $projectContext;
        $this->project = $project;
        $this->object = $object;
    }

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, array $args)
    {
        $this->projectContext->authenticate($this->project);

        $result = call_user_func_array(array($this->object, $name), $args);

        $this->projectContext->logout();

        return $result;
    }
}
