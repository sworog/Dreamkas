<?php

namespace Lighthouse\CoreBundle\Security\Voter;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.security.voter.store_manager")
 * @DI\Tag("security.voter")
 */
class StoreManagerVoter implements VoterInterface
{
    const ACL_STORE_MANAGER = 'ACL_STORE_MANAGER';
    const ACL_DEPARTMENT_MANAGER = 'ACL_DEPARTMENT_MANAGER';

    /**
     * @var array
     */
    protected $supportedAttributes = array(
        self::ACL_STORE_MANAGER => Store::REL_STORE_MANAGERS,
        self::ACL_DEPARTMENT_MANAGER => Store::REL_DEPARTMENT_MANAGERS,
    );

    /**
     * @var array
     */
    protected $roleAttributes = array(
        self::ACL_STORE_MANAGER => User::ROLE_STORE_MANAGER,
        self::ACL_DEPARTMENT_MANAGER => User::ROLE_DEPARTMENT_MANAGER
    );

    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return Boolean true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        if (array_key_exists($attribute, $this->supportedAttributes)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * @param TokenInterface $token
     * @param object|Store $object
     * @param array $attributes
     * @return int|void
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;

        if ($object instanceof Store) {
            $user = $token->getUser();
            foreach ($attributes as $attribute) {
                if ($this->supportsAttribute($attribute)) {
                    $result = VoterInterface::ACCESS_DENIED;
                    $managers = $object->getManagersByRel($this->supportedAttributes[$attribute]);
                    if ($managers->contains($user)) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getRoleByAttribute($attribute)
    {
        if (isset($this->roleAttributes[$attribute])) {
            return $this->roleAttributes[$attribute];
        }
    }

    /**
     * @param array $attributes
     * @return array roles
     */
    public function getRolesByAttributes(array $attributes)
    {
        $roles = array();
        foreach ($attributes as $attribute) {
            if ($this->supportsAttribute($attribute)) {
                $roles[] = $this->getRoleByAttribute($attribute);
            }
        }
        return $roles;
    }
}
