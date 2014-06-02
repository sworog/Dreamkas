<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Security\Project\ProjectToken;
use Symfony\Component\Security\Core\SecurityContextInterface;

class RunAsProjectInvokerProxy
{
    /**
     * @var ProjectAuthenticationProvider
     */
    protected $provider;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var object
     */
    protected $object;

    /**
     * @param ProjectAuthenticationProvider $provider
     * @param Project $project
     * @param $object
     */
    public function __construct(ProjectAuthenticationProvider $provider, Project $project, $object)
    {
        $this->provider = $provider;
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
        $this->provider->authenticate($this->project);

        $result = call_user_func_array(array($this->object, $name), $args);

        $this->provider->logout();

        return $result;
    }
}
