<?php

namespace Lighthouse\CoreBundle\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Security\Core\User\EquatableInterface;
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
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\User\UserRepository",
 *      indexes={
 *          @MongoDB\Index(keys={"username"="desc"}, options={"unique"=true})
 *      }
 * )
 * @Unique(fields="username", message="lighthouse.validation.errors.user.username.unique")
 */
class User extends AbstractDocument implements UserInterface, EquatableInterface
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
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $user instance may implement
     * the extended user interface `AdvancedUserInterface`.
     *
     * @param UserInterface $user
     *
     * @return Boolean
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array($this->role);
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
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
     * This can return null if the password was not encoded using a salt.
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
