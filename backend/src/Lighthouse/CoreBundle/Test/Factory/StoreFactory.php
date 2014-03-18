<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Document\User\User;

class StoreFactory extends AbstractFactory
{
    const STORE_DEFAULT_NUMBER = '1';

    /**
     * @var array|Store[]
     */
    protected $stores = array();

    /**
     * @var array number => id
     */
    protected $storeNumbers = array();

    /**
     * @var User[]
     */
    protected $storeManagers = array();

    /**
     * @var User[]
     */
    protected $departmentManagers = array();

    /**
     * @param string $number
     * @param string $address
     * @param string $contacts
     * @return Store
     */
    public function createStore(
        $number = self::STORE_DEFAULT_NUMBER,
        $address = self::STORE_DEFAULT_NUMBER,
        $contacts = self::STORE_DEFAULT_NUMBER
    ) {
        $store = new Store();
        $store->number = $number;
        $store->address = $address;
        $store->contacts = $contacts;

        $this->getDocumentManager()->persist($store);
        $this->getDocumentManager()->flush();

        $this->storeNumbers[$number] = $store->id;

        return $store;
    }

    /**
     * @param string $number
     * @param string $address
     * @param string $contacts
     * @return string
     */
    public function getStoreId(
        $number = self::STORE_DEFAULT_NUMBER,
        $address = self::STORE_DEFAULT_NUMBER,
        $contacts = self::STORE_DEFAULT_NUMBER
    ) {
        return $this->getStore($number, $address, $contacts)->id;
    }

    /**
     * @param string $number
     * @param string $address
     * @param string $contacts
     * @return Store
     */
    public function getStore(
        $number = self::STORE_DEFAULT_NUMBER,
        $address = self::STORE_DEFAULT_NUMBER,
        $contacts = self::STORE_DEFAULT_NUMBER
    ) {
        if (!isset($this->storeNumbers[$number])) {
            $store = $this->createStore($number, $address, $contacts);

        }
        return $this->getStoreById($this->storeNumbers[$number]);
    }

    /**
     * @param array $numbers
     * @return array number => storeId
     */
    public function getStores(array $numbers)
    {
        $storeIds = array();
        foreach ($numbers as $number) {
            $storeIds[$number] = $this->getStoreId($number);
        }
        return $storeIds;
    }

    /**
     * @param string $storeId
     * @return Store
     * @throws \RuntimeException
     */
    public function getStoreById($storeId)
    {
        $store = $this->getStoreRepository()->find($storeId);
        if (null === $store) {
            throw new \RuntimeException(sprintf('Store id#%s not found', $storeId));
        }
        return $store;
    }

    /**
     * @return StoreRepository
     */
    protected function getStoreRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.store');
    }

    /**
     * @param string $storeId
     * @param array $userIds
     * @param string $rel
     */
    public function linkManagers($storeId, $userIds, $rel)
    {
        $store = $this->getStoreById($storeId);
        $managers = $store->getManagersByRel($rel);
        foreach ((array) $userIds as $userId) {
            $user = $this->factory->user()->getUserById($userId);
            $managers->add($user);
        }
        $this->getDocumentManager()->persist($store);
        $this->getDocumentManager()->flush();
    }

    /**
     * @param array $userIds
     * @param string $storeId
     */
    public function linkStoreManagers($userIds, $storeId = null)
    {
        $storeId = ($storeId) ?: $this->getStoreId();
        $this->linkManagers($storeId, $userIds, Store::REL_STORE_MANAGERS);
    }

    /**
     * @param array $userIds
     * @param string $storeId
     */
    public function linkDepartmentManagers($userIds, $storeId = null)
    {
        $storeId = ($storeId) ?: $this->getStoreId();
        $this->linkManagers($storeId, $userIds, Store::REL_DEPARTMENT_MANAGERS);
    }

    /**
     * @param string $storeId
     * @return User
     */
    public function getStoreManager($storeId = null)
    {
        $storeId = ($storeId) ?: $this->getStoreId();
        if (!isset($this->storeManagers[$storeId])) {
            $username = 'storeManagerStore' . $storeId;
            $manager = $this->factory->user()->getUser(
                $username,
                UserFactory::USER_DEFAULT_PASSWORD,
                User::ROLE_STORE_MANAGER
            );
            $this->linkStoreManagers($manager->id, $storeId);

            $this->storeManagers[$storeId] = $manager;
        }
        return $this->storeManagers[$storeId];
    }

    /**
     * @param string $storeId
     * @return User
     */
    public function getDepartmentManager($storeId = null)
    {
        $storeId = ($storeId) ?: $this->getStoreId();
        if (!isset($this->departmentManagers[$storeId])) {
            $username = 'departmentManagerStore' . $storeId;
            $manager = $this->factory->user()->getUser(
                $username,
                UserFactory::USER_DEFAULT_PASSWORD,
                User::ROLE_DEPARTMENT_MANAGER
            );
            $this->linkDepartmentManagers($manager->id, $storeId);

            $this->departmentManagers[$storeId] = $manager;
        }
        return $this->departmentManagers[$storeId];
    }
}
