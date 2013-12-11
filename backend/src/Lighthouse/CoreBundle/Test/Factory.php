<?php

namespace Lighthouse\CoreBundle\Test;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Test\Client\Client;
use Lighthouse\CoreBundle\Document\Auth\Client as AuthClient;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Versionable\VersionRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DateTime;

class Factory
{
    const CLIENT_DEFAULT_SECRET = 'secret';

    const USER_DEFAULT_PASSWORD = 'password';
    const STORE_DEFAULT_NUMBER = '1';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var AuthClient
     */
    protected $authClient;

    /**
     * @var User[]
     */
    protected $users = array();

    /**
     * @var array
     */
    protected $storeManagers;

    /**
     * @var array
     */
    protected $departmentManagers;

    /**
     * @var array
     */
    protected $accessTokens = array();

    /**
     * @var array
     */
    protected $stores = array();

    /**
     * @var array
     */
    protected $storeProducts = array();

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->client = $this->container->get('test.client');
        $this->dm = $this->container->get('doctrine_mongodb.odm.document_manager');
    }

    /**
     * @return AuthClient
     */
    public function getAuthClient()
    {
        if (null === $this->authClient) {
            $this->authClient = $this->createAuthClient();
        }
        return $this->authClient;
    }

    /**
     * @param string $secret
     * @return AuthClient
     */
    public function createAuthClient($secret = self::CLIENT_DEFAULT_SECRET)
    {
        $client = new AuthClient;
        $client->setSecret($secret);

        $this->dm->persist($client);
        $this->dm->flush();

        return $client;
    }

    /**
     * @param User $oauthUser
     * @param string $password
     * @param AuthClient $oauthClient
     * @return \stdClass access token
     */
    public function doAuth(User $oauthUser, $password = self::USER_DEFAULT_PASSWORD, AuthClient $oauthClient = null)
    {
        $oauthClient = ($oauthClient) ?: $this->getAuthClient();

        $authParams = array(
            'grant_type' => 'password',
            'username' => $oauthUser->username,
            'password' => $password,
            'client_id' => $oauthClient->getPublicId(),
            'client_secret' => $oauthClient->getSecret()
        );

        $this->client->request(
            'POST',
            '/oauth/v2/token',
            $authParams,
            array(),
            array('Content-Type' => 'application/x-www-form-urlencoded')
        );

        $content = $this->client->getResponse()->getContent();
        $token = json_decode($content);

        return $token;
    }

    /**
     * @param User $oauthUser
     * @param string $password
     * @param AuthClient $oauthClient
     * @return \stdClass
     */
    public function auth(User $oauthUser, $password = self::USER_DEFAULT_PASSWORD, AuthClient $oauthClient = null)
    {
        if (self::USER_DEFAULT_PASSWORD === $password && null === $oauthClient) {
            if (!isset($this->accessTokens[$oauthUser->id])) {
                $this->accessTokens[$oauthUser->id] = $this->doAuth($oauthUser, $password, $oauthClient);
            }
            return $this->accessTokens[$oauthUser->id];
        } else {
            return $this->doAuth($oauthUser, $password, $oauthClient);
        }
    }

    /**
     * @param string $role
     * @return \stdClass
     */
    public function authAsRole($role)
    {
        $user = $this->getRoleUser($role);
        return $this->auth($user);
    }

    /**
     * @param string $role
     * @return User
     */
    public function getRoleUser($role)
    {
        return $this->getUser($role, self::USER_DEFAULT_PASSWORD, $role, $role, $role);
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $position
     *
     * @return User
     */
    public function getUser(
        $username = 'admin',
        $password = self::USER_DEFAULT_PASSWORD,
        $role = User::ROLE_ADMINISTRATOR,
        $name = 'Админ Админыч',
        $position = 'Администратор'
    ) {
        $hash = md5(implode(',', func_get_args()));
        if (!isset($this->users[$hash])) {
            $this->users[$hash] = $this->createUser($username, $password, $role, $name, $position);
        }
        return $this->users[$hash];
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $position
     * @return User
     */
    public function createUser(
        $username = 'admin',
        $password = self::USER_DEFAULT_PASSWORD,
        $role = User::ROLE_ADMINISTRATOR,
        $name = 'Админ Админыч',
        $position = 'Администратор'
    ) {
        /* @var UserRepository $userRepository */
        $userRepository = $this->container->get('lighthouse.core.document.repository.user');
        /* @var UserProvider $userProvider */
        $userProvider = $this->container->get('lighthouse.core.user.provider');

        $user = new User();
        $user->name = $name;
        $user->username = $username;
        $user->role = $role;
        $user->position = $position;

        $userProvider->setPassword($user, $password);

        $userRepository->getDocumentManager()->persist($user);
        $userRepository->getDocumentManager()->flush();

        return $user;
    }

    /**
     * @param string $storeId
     * @return User
     */
    public function getStoreManager($storeId = null)
    {
        $storeId = $this->getStoreById($storeId);
        if (!isset($this->storeManagers[$storeId])) {
            $username = 'storeManagerStore' . $storeId;
            $manager = $this->getUser($username, self::USER_DEFAULT_PASSWORD, User::ROLE_STORE_MANAGER);
            $this->linkStoreManagers($manager->id, $storeId);

            $this->storeManagers[$storeId] = $manager;
        }
        return $this->storeManagers[$storeId];
    }

    /**
     * @param string $storeId
     * @return \stdClass
     */
    public function authAsStoreManager($storeId = null)
    {
        $storeId = $this->getStoreById($storeId);
        $storeManager = $this->getStoreManager($storeId);
        return $this->auth($storeManager);
    }

    /**
     * @param string $storeId
     * @return User
     */
    public function getDepartmentManager($storeId = null)
    {
        $storeId = $this->getStoreById($storeId);
        if (!isset($this->departmentManagers[$storeId])) {
            $username = 'departmentManagerStore' . $storeId;
            $manager = $this->getUser($username, self::USER_DEFAULT_PASSWORD, User::ROLE_DEPARTMENT_MANAGER);
            $this->linkDepartmentManagers($manager->id, $storeId);

            $this->departmentManagers[$storeId] = $manager;
        }
        return $this->departmentManagers[$storeId];
    }

    /**
     * @param string $storeId
     * @return \stdClass
     */
    public function authAsDepartmentManager($storeId = null)
    {
        $storeId = $this->getStoreById($storeId);
        $storeManager = $this->getDepartmentManager($storeId);
        return $this->auth($storeManager);
    }

    /**
     * @param string $storeId
     * @param array $userIds
     * @param string $rel
     */
    public function linkManagers($storeId, $userIds, $rel)
    {
        $userIds = (array) $userIds;

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        foreach ($userIds as $userId) {
            $request->addLinkHeader($this->getUserResourceUri($userId), $rel);
        }

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $this->client->jsonRequest($request, $accessToken);

        Assert::assertResponseCode(204, $this->client);
    }

    /**
     * @param array $userIds
     * @param string $storeId
     */
    public function linkStoreManagers($userIds, $storeId = null)
    {
        $storeId = $this->getStoreById($storeId);
        $this->linkManagers($storeId, $userIds, Store::REL_STORE_MANAGERS);
    }

    /**
     * @param array $userIds
     * @param string $storeId
     */
    public function linkDepartmentManagers($userIds, $storeId = null)
    {
        $storeId = $this->getStoreById($storeId);
        $this->linkManagers($storeId, $userIds, Store::REL_DEPARTMENT_MANAGERS);
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
        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->client->jsonRequest($jsonRequest, $accessToken);

        Assert::assertResponseCode(201, $this->client);
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

    /**
     * @param string $userId
     * @return string
     */
    public function getUserResourceUri($userId)
    {
        return sprintf('http://localhost/api/1/users/%s', $userId);
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @return string
     */
    public function getStoreProduct($storeId, $productId)
    {
        if (!isset($this->storeProducts[$storeId]) || !isset($this->storeProducts[$storeId][$productId])) {
            $this->storeProducts[$storeId][$productId] = $this
                ->getStoreProductRepository()
                ->findByStoreIdProductId($storeId, $productId);
        }

        return $this->storeProducts[$storeId][$productId];
    }

    /**
     * @param array $sales
     * @return Sale[]
     */
    public function createSales(array $sales)
    {
        $self = $this;
        $saleModels = array_map(
            function ($sale) use ($self) {
                $saleModel = $self->createSale($sale['storeId'], $sale['createdDate'], $sale['sumTotal']);
                array_map(
                    function ($position) use ($self, $saleModel) {
                        $self->createSaleProduct(
                            $position['price'],
                            $position['quantity'],
                            $position['productId'],
                            $saleModel
                        );
                    },
                    $sale['positions']
                );
                return $saleModel;
            },
            $sales
        );

        $this->flush();

        return $saleModels;
    }

    public function flush()
    {
        $this->dm->flush();
    }

    /**
     * @param string $storeId
     * @param string $createdDate
     * @param string $sumTotal
     * @return Sale
     */
    public function createSale($storeId, $createdDate, $sumTotal)
    {
        $saleModel = new Sale();
        $saleModel->createdDate = new DateTime($createdDate);
        $saleModel->store = $this->dm->getReference(Store::getClassName(), $storeId);
        $saleModel->hash = md5(rand() . $storeId);
        $saleModel->sumTotal = $this->getNumericFactory()->createMoney($sumTotal);

        $this->dm->persist($saleModel);

        return $saleModel;
    }
    /**
     * @param Money|string $price
     * @param Decimal|float $quantity
     * @param string $productId
     * @param Sale $sale
     * @return SaleProduct
     */
    public function createSaleProduct($price, $quantity, $productId, Sale $sale = null)
    {
        $saleProduct = new SaleProduct();
        $saleProduct->price = $this->getNumericFactory()->createMoney($price);
        $saleProduct->quantity = $this->getNumericFactory()->createQuantity($quantity);
        $saleProduct->product = $this->createProductVersion($productId);
        if ($sale) {
            $saleProduct->sale = $sale;
            $sale->products->add($saleProduct);
        }

        $this->dm->persist($saleProduct);

        return $saleProduct;
    }

    /**
     * @param string $productId
     * @return ProductVersion
     */
    public function createProductVersion($productId)
    {
        return $this->getProductVersionRepository()->findOrCreateByDocumentId($productId);
    }

    /**
     * @return NumericFactory
     */
    protected function getNumericFactory()
    {
        return $this->container->get('lighthouse.core.types.numeric.factory');
    }

    /**
     * @return VersionRepository
     */
    protected function getProductVersionRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.product_version');
    }

    /**
     * @return StoreProductRepository
     */
    protected function getStoreProductRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.store_product');
    }
}
