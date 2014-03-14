<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Client;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;

class StoreFactory extends AbstractFactoryFactory
{
    const STORE_DEFAULT_NUMBER = '1';

    /**
     * @var array|Store[]
     */
    protected $stores = array();

    /**
     * @var Client
     */
    protected $client;

    /**
     * @return Client
     */
    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = $this->container->get('test.client');
        }
        return $this->client;
    }

    /**
     * @param string $number
     * @param string $address
     * @param string $contacts
     * @return mixed
     */
    public function createStore(
        $number = self::STORE_DEFAULT_NUMBER,
        $address = self::STORE_DEFAULT_NUMBER,
        $contacts = self::STORE_DEFAULT_NUMBER
    ) {
        $storeData = array(
            'number' => $number,
            'address' => $address,
            'contacts' => $contacts,
        );
        $jsonRequest = new JsonRequest('/api/1/stores', 'POST', $storeData);
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->getClient()->jsonRequest($jsonRequest, $accessToken);

        Assert::assertResponseCode(201, $this->getClient());
        Assert::assertJsonHasPath('id', $response);

        return $response['id'];
    }

    /**
     * @param string $number
     * @return mixed
     */
    public function getStore($number = self::STORE_DEFAULT_NUMBER)
    {
        if (!isset($this->stores[$number])) {
            $this->stores[$number] = $this->createStore($number, $number, $number);
        }
        return $this->stores[$number];
    }

    /**
     * @param array $numbers
     * @return array number => storeId
     */
    public function getStores(array $numbers)
    {
        $storeIds = array();
        foreach ($numbers as $number) {
            $storeIds[$number] = $this->getStore($number);
        }
        return $storeIds;
    }

    /**
     * @param string $storeId
     * @return string
     */
    public function getStoreById($storeId = null)
    {
        return $storeId ?: $this->getStore();
    }
}
