<?php

namespace Lighthouse\CoreBundle\Document\Project;

use Lighthouse\CoreBundle\Security\Token\ProjectToken;
use Symfony\Component\Security\Core\SecurityContextInterface;

class RunAsProjectInvokerProxy
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var object
     */
    protected $object;

    /**
     * @param SecurityContextInterface $securityContext
     * @param Project $project
     * @param $object
     */
    public function __construct(SecurityContextInterface $securityContext, Project $project, $object)
    {
        $this->securityContext = $securityContext;
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
        $projectToken = new ProjectToken($this->project, $this->securityContext->getToken());

        $this->securityContext->setToken($projectToken);

        $result = call_user_func_array(array($this->object, $name), $args);

        $this->securityContext->setToken($projectToken->getOriginalToken());

        return $result;
    }
}
