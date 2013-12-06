<?php

namespace Lighthouse\CoreBundle\DataFixtures\Kesko;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadStoresData extends ContainerAware implements DependentFixtureInterface, FixtureInterface
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
                'lighthouse',
                'Сторье #' . $storeNumber,
                User::ROLE_STORE_MANAGER,
                'Директор магазина'
            );

            $departmentManager = $userProvider->createNewUser(
                'departmentManager' . $storeNumber,
                'lighthouse',
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
            'Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData',
            'Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData',
        );
    }
}
