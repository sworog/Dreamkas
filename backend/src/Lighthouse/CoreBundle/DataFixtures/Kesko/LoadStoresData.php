<?php

namespace Lighthouse\Kesko;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Fixture\Sorter\DependentFixture;
use FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadStoresData extends ContainerAware implements DependentFixture, DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userProvider = $this->container->get('lighthouse.core.user.provider');

        foreach (array(701, 702, 703, 704) as $storeNumber) {
            $store = new Store();
            $store->number = $storeNumber;
            $store->address = 'Ул. Кеско, д. ' . $storeNumber;
            $store->contacts = '911-888-7-' . $storeNumber;

            $storeManager = $userProvider->createNewUser(
                'storeManager' . $storeNumber,
                'password',
                'Сторье #' . $storeNumber,
                User::ROLE_STORE_MANAGER,
                'Директор магазина'
            );

            $departmentManager = $userProvider->createNewUser(
                'departmentManager' . $storeNumber,
                'password',
                'Депардье #' . $storeNumber,
                User::ROLE_DEPARTMENT_MANAGER,
                'Зав. отдела'
            );

            $store->storeManagers->add($storeManager);
            $store->departmentManagers->add($departmentManager);
            $manager->persist($store);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array(
            'Lighthouse\\CoreBundle\\DataFixtures\\ODM\\LoadApiClientData',
            'Lighthouse\\CoreBundle\\DataFixtures\\ODM\\LoadUserData',
        );
    }
}
