<?php

namespace Lighthouse\CoreBundle\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Store\Store;
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

        foreach ($this->getStoresData() as $storeNumber => $storeData) {
            $store = new Store();
            $store->number = $storeNumber;
            $store->address = (isset($storeData['address'])) ? $storeData['address'] : 'Ул. Кеско, д. ' . $storeNumber;
            $store->contacts = (isset($storeData['contacts'])) ? $storeData['contacts'] : '911-888-7-' . $storeNumber;

            $store->storeManagers->add($ownerUser);
            $store->departmentManagers->add($ownerUser);
            $manager->persist($store);
        }

        $manager->flush();

        $this->updateIndexes($manager);
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
            'Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadApiClientData',
            'Lighthouse\\CoreBundle\\DataFixtures\\MongoDB\\LoadUserData',
        );
    }
}
