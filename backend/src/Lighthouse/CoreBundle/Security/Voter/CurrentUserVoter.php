<?php

namespace Lighthouse\CoreBundle\Security\Voter;

use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.security.voter.current_user")
 * @DI\Tag("security.voter")
 */
class CurrentUserVoter implements VoterInterface
{
    const ACL_CURRENT_USER = 'ACL_CURRENT_USER';

    /**
     * @param string $attribute
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        if (self::ACL_CURRENT_USER == $attribute) {
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
     * @param object $object
     * @param array $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $currentUser = $token->getUser();

        if ($object instanceof User) {
            foreach ($attributes as $attribute) {
                if ($this->supportsAttribute($attribute)) {
                    if ($object === $currentUser) {
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
