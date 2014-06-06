<?php

namespace Lighthouse\CoreBundle\Security\Project;

use Lighthouse\CoreBundle\Document\Project\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

class ProjectToken implements TokenInterface
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var TokenInterface
     */
    protected $originalToken;

    /**
     * @param Project $project
     * @param TokenInterface $originalToken
     * @param array $roles
     */
    public function __construct(Project $project, TokenInterface $originalToken = null, array $roles = array())
    {
        $this->project = $project;
        $this->originalToken = $originalToken;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(
            array(
                clone $this->project,
                $this->roles,
                $this->originalToken
            )
        );
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($this->project, $this->roles, $this->originalToken) = unserialize($serialized);
    }

    /**
     * @return RoleInterface[]|array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return mixed|string
     */
    public function getCredentials()
    {
        return '';
    }

    /**
     * @return mixed|string
     */
    public function getUser()
    {
        return $this->project->getName();
    }

    /**
     * @param mixed $user
     * @throws \Exception
     */
    public function setUser($user)
    {
        throw new \Exception('Should not be called');
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->project->getName();
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return true;
    }

    /**
     * @param bool $isAuthenticated
     * @throws \Exception
     */
    public function setAuthenticated($isAuthenticated)
    {
        throw new \Exception('Should not be called');
    }

    /**
     *
     */
    public function eraseCredentials()
    {

    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return array();
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {

    }

    /**
     * @param string $name
     * @return bool|void
     */
    public function hasAttribute($name)
    {

    }

    /**
     * @param string $name
     * @return mixed|void
     */
    public function getAttribute($name)
    {

    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Project %s', $this->project->getName());
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return TokenInterface
     */
    public function getOriginalToken()
    {
        return $this->originalToken;
    }

    /**
     * @return bool
     */
    public function hasOriginalToken()
    {
        return null !== $this->originalToken;
    }
}
