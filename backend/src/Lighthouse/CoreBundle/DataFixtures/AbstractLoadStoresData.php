<?php

namespace Lighthouse\CoreBundle\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\DataFixtures\MongoDB\LoadApiClientData;
use Lighthouse\CoreBundle\DataFixtures\MongoDB\LoadUserData;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\DependencyInjection\ContainerAware;

abstract class AbstractLoadStoresData extends ContainerAware implements DependentFixtureInterface, FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userProvider = $this->container->get('lighthouse.core.user.provider');
        $projectContext = $this->container->get('project.context');

        $ownerUser = $userProvider->loadUserByUsername('owner@lighthouse.pro');
        $project = $ownerUser->getProject();
        $projectContext->authenticate($project);

        $this->loadStores($manager, $ownerUser);

        $this->updateIndexes($manager);
    }

    /**
     * @param ObjectManager $manager
     * @param User $ownerUser
     */
    protected function loadStores(ObjectManager $manager, User $ownerUser)
    {
        foreach ($this->getStoresData() as $storeName => $storeData) {
            $store = new Store();
            $store->name = $storeName;
            $store->address = (isset($storeData['address'])) ? $storeData['address'] : 'Ул. Кеско, д. ' . $storeName;
            $store->contacts = (isset($storeData['contacts'])) ? $storeData['contacts'] : '911-888-7-' . $storeName;

            $store->storeManagers->add($ownerUser);
            $store->departmentManagers->add($ownerUser);
            $manager->persist($store);
        }

        $manager->flush();
    }

    /**
     * @param DocumentManager|ObjectManager $dm
     */
    protected function updateIndexes(ObjectManager $dm)
    {
        $dm->getSchemaManager()->updateIndexes();
    }

    /**
     * @return array
     */
    abstract public function getStoresData();

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array(
            LoadApiClientData::getClassName(),
            LoadUserData::getClassName(),
        );
    }
}
