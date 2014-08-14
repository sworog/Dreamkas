<?php

namespace Lighthouse\CoreBundle\DataFixtures\MongoDB;

use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\User\UserProvider;

class LoadUserData extends AbstractFixture
{
    /**
     * @return UserProvider
     */
    protected function getUserProvider()
    {
        return $this->container->get('lighthouse.core.user.provider');
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->getUserProvider()->createNewUser(
            'owner@lighthouse.pro',
            'lighthouse',
            'Юрий Константиныч',
            array(
                User::ROLE_COMMERCIAL_MANAGER,
                User::ROLE_DEPARTMENT_MANAGER,
                User::ROLE_STORE_MANAGER,
                User::ROLE_ADMINISTRATOR
            ),
            'Владелец сети'
        );
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 20;
    }
}
