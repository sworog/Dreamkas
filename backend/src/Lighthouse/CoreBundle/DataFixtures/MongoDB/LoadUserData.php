<?php

namespace Lighthouse\CoreBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadUserData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
            'administrator@lighthouse.pro',
            'lighthouse',
            'Сережа',
            User::ROLE_ADMINISTRATOR,
            'Администратор'
        );

        $this->getUserProvider()->createNewUser(
            'commercialManager@lighthouse.pro',
            'lighthouse',
            'Юрий Константиныч',
            User::ROLE_COMMERCIAL_MANAGER,
            'Коммерческий директор сети'
        );

        $this->getUserProvider()->createNewUser(
            'departmentManager@lighthouse.pro',
            'lighthouse',
            'Наталья Павловна',
            User::ROLE_DEPARTMENT_MANAGER,
            'Зав. секции'
        );

        $this->getUserProvider()->createNewUser(
            'storeManager@lighthouse.pro',
            'lighthouse',
            'Михаил Сергеевич',
            User::ROLE_STORE_MANAGER,
            'и.о. директора магазина'
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
