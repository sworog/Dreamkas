<?php

namespace Lighthouse\CoreBundle\Security\Token;

use Lighthouse\CoreBundle\Document\Project\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
     * Returns the user roles.
     *
     * @return RoleInterface[] An array of RoleInterface instances.
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }

    /**
     * Returns a user representation.
     *
     * @return mixed either returns an object which implements __toString(), or
     *                  a primitive string is returned.
     */
    public function getUser()
    {
        return $this->project->getNamespace();
    }

    /**
     * Sets a user.
     *
     * @param mixed $user
     */
    public function setUser($user)
    {
        throw new \Exception('Should not be called');
    }

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->project->getNamespace();
    }

    /**
     * Returns whether the user is authenticated or not.
     *
     * @return bool    true if the token has been authenticated, false otherwise
     */
    public function isAuthenticated()
    {
        return true;
    }

    /**
     * Sets the authenticated flag.
     *
     * @param bool $isAuthenticated The authenticated flag
     */
    public function setAuthenticated($isAuthenticated)
    {
        throw new \Exception('Should not be called');
    }

    /**
     * Removes sensitive information from the token.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Returns the token attributes.
     *
     * @return array The token attributes
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
        // TODO: Implement hasAttribute() method.
    }

    /**
     * @param string $name
     * @return mixed|void
     */
    public function getAttribute($name)
    {
        // TODO: Implement getAttribute() method.
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        // TODO: Implement setAttribute() method.
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Project %s', $this->project->getNamespace());
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
