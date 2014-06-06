<?php

namespace Lighthouse\CoreBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadStoresData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $store666 = new Store();
        $store666->number = '666';
        $store666->address = 'ул. Вязов, д.666';
        $store666->contacts = '+7 666 666-66-66';

        $store777 = new Store();
        $store777->number = '777';
        $store777->address = 'ул. Гемблинга, д.777';
        $store777->contacts = '+7 777 777-77-77';

        $store888 = new Store();
        $store888->number = '888';
        $store888->address = 'ул. Паблик Морозова, д.14 кв.88';
        $store888->contacts = '+7 888 888-88-88';

        /* @var UserProvider $userProvider */
        $userProvider = $this->container->get('lighthouse.core.user.provider');

        $storeMan666 = $userProvider->createNewUser(
            'storeManager666@lighthouse.pro',
            'lighthouse',
            'Петров А.Д.',
            User::ROLE_STORE_MANAGER,
            'Директор магазина'
        );

        $depMan666 = $userProvider->createNewUser(
            'departmentManager666@lighthouse.pro',
            'lighthouse',
            'Сидоров А.Д.',
            User::ROLE_DEPARTMENT_MANAGER,
            'Зав. отдела'
        );

        $storeMan777 = $userProvider->createNewUser(
            'storeManager777@lighthouse.pro',
            'lighthouse',
            'Казиновмч Л.В.',
            User::ROLE_STORE_MANAGER,
            'Директор магазина'
        );

        $depMan777 = $userProvider->createNewUser(
            'departmentManager777@lighthouse.pro',
            'lighthouse',
            'Игроков Б.Дж.',
            User::ROLE_DEPARTMENT_MANAGER,
            'Зав. отдела'
        );

        $storeMan888 = $userProvider->createNewUser(
            'storeManager888@lighthouse.pro',
            'lighthouse',
            'Морозов П.Т.',
            User::ROLE_STORE_MANAGER,
            'Директор магазина'
        );

        $depMan888 = $userProvider->createNewUser(
            'departmentManager888@lighthouse.pro',
            'lighthouse',
            'Морозов Ф.Т.',
            User::ROLE_DEPARTMENT_MANAGER,
            'Зав. отдела'
        );

        $store666->storeManagers->add($storeMan666);
        $store666->departmentManagers->add($depMan666);
        $store777->storeManagers->add($storeMan777);
        $store777->departmentManagers->add($depMan777);
        $store888->storeManagers->add($storeMan888);
        $store888->departmentManagers->add($depMan888);

        $manager->persist($store666);
        $manager->persist($store777);
        $manager->persist($store888);

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 30;
    }
}
