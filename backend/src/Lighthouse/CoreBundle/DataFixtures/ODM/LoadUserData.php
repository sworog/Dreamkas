<?php

namespace Lighthouse\CoreBundle\DataFixtures\ODM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadUserData extends ContainerAware implements FixtureInterface
{
    /**
     * @return \Lighthouse\CoreBundle\Security\User\UserProvider
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
            'administrator',
            'lighthouse',
            'Сережа',
            User::ROLE_ADMINISTRATOR,
            'Администратор'
        );

        $this->getUserProvider()->createNewUser(
            'commercialManager',
            'lighthouse',
            'Юрий Константиныч',
            User::ROLE_COMMERCIAL_MANAGER,
            'Коммерческий директор сети'
        );

        $this->getUserProvider()->createNewUser(
            'departmentManager',
            'lighthouse',
            'Наталья Павловна',
            User::ROLE_DEPARTMENT_MANAGER,
            'Зав. секции'
        );

        $this->getUserProvider()->createNewUser(
            'storeManager',
            'lighthouse',
            'Михаил Сергеевич',
            User::ROLE_STORE_MANAGER,
            'и.о. директора магазина'
        );
    }
}
