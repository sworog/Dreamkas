<?php

namespace Lighthouse\CoreBundle\Security\Voter;

use Lighthouse\CoreBundle\Document\Store\Store;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.security.voter.store_manager")
 * @DI\Tag("security.voter")
 */
class StoreManagerVoter implements VoterInterface
{
    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return Boolean true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        if ('ACL_STORE_MANAGER' == $attribute) {
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
        $user = $token->getUser();

        foreach ($attributes as $attribute) {
            if ($this->supportsAttribute($attribute)) {
                if ($object instanceof Store) {
                    if ($object->storeManagers->contains($user)) {
                        return VoterInterface::ACCESS_GRANTED;
                    } else {
                        return VoterInterface::ACCESS_DENIED;
                    }
                }
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
