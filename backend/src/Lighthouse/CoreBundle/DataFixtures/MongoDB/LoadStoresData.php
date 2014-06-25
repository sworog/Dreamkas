<?php

namespace Lighthouse\CoreBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Security\User\UserProvider;
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
        $projectContext = $this->container->get('project.context');

        $ownerUser = $userProvider->loadUserByUsername("owner@lighthouse.pro");
        $project = $ownerUser->getProject();
        $projectContext->authenticate($project);

        $store666->storeManagers->add($ownerUser);
        $store666->departmentManagers->add($ownerUser);
        $store777->storeManagers->add($ownerUser);
        $store777->departmentManagers->add($ownerUser);
        $store888->storeManagers->add($ownerUser);
        $store888->departmentManagers->add($ownerUser);

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
