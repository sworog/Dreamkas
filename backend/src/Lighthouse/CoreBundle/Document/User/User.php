<?php

namespace Lighthouse\CoreBundle\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Project\Project;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Lighthouse\CoreBundle\MongoDB\Mapping\Annotations\GlobalDb;

/**
 *
 * @property string $id
 * @property string $email
 * @property string $name
 * @property string $position
 * @property string $password
 * @property string $salt
 * @property string $role
 * @property Project $project
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\User\UserRepository")
 * @Unique(fields="email", message="lighthouse.validation.errors.user.email.unique")
 * @GlobalDb
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
     * @Assert\NotBlank(groups={"Default", "registration"})
     * @Assert\Email(groups={"Default", "registration"})
     * @var string
     */
    protected $email;

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
     * @MongoDB\Collection
     * @Assert\NotBlank
     * @Assert\Choice(
     *      choices={
     *          "ROLE_COMMERCIAL_MANAGER",
     *          "ROLE_STORE_MANAGER",
     *          "ROLE_DEPARTMENT_MANAGER",
     *          "ROLE_ADMINISTRATOR"
     *      },
     *      multiple=true
     * )
     * @var array
     */
    protected $roles;

    /**
     * @MongoDB\String
     * @Assert\NotBlank(groups={"creation"})
     * @Assert\Length(min="6")
     * @LighthouseAssert\NotEqualsField(
     *      field = "email",
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
     * @MongoDB\ReferenceOne(
     *      targetDocument="Lighthouse\CoreBundle\Document\Project\Project",
     *      simple=true,
     *      cascade="persist"
     * )
     * @var Project
     */
    protected $project;

    /**
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
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
        return $this->email;
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

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
