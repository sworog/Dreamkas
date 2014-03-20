<?php

namespace Lighthouse\CoreBundle\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

/**
 *
 * @property string $id
 * @property string $username
 * @property string $name
 * @property string $position
 * @property string $password
 * @property string $salt
 * @property string $role
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\User\UserRepository")
 * @Unique(fields="username", message="lighthouse.validation.errors.user.username.unique")
 */
class User extends AbstractDocument implements UserInterface
{
    const ROLE_COMMERCIAL_MANAGER = "ROLE_COMMERCIAL_MANAGER";
    const ROLE_STORE_MANAGER = "ROLE_STORE_MANAGER";
    const ROLE_DEPARTMENT_MANAGER = "ROLE_DEPARTMENT_MANAGER";
    const ROLE_ADMINISTRATOR = "ROLE_ADMINISTRATOR";

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @Assert\Regex("/^[\w\d_\-\.\@]+$/u")
     * @var string
     */
    protected $username;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $position;

    /**
     * @MongoDB\string
     * @Assert\NotBlank
     * @Assert\Choice({
     *      "ROLE_COMMERCIAL_MANAGER",
     *      "ROLE_STORE_MANAGER",
     *      "ROLE_DEPARTMENT_MANAGER",
     *      "ROLE_ADMINISTRATOR"
     * })
     * @var string
     */
    protected $role;

    /**
     * @MongoDB\String
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(min="6")
     * @LighthouseAssert\NotEqualsField(
     *      field = "username",
     *      message = "lighthouse.validation.errors.user.password.not_equals_login"
     * )
     * @Serializer\Exclude
     * @var string
     */
    protected $password;

    /**
     * @MongoDB\String
     * @Serializer\Exclude
     * @var string
     */
    protected $salt;

    /**
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array($this->role);
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasUserRole($role)
    {
        return in_array($role, $this->getRoles());
    }
}
