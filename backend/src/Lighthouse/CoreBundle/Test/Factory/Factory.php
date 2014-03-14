<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\File\File;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Receipt\ReceiptRepository;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\Client;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Versionable\VersionRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Factory extends AbstractFactory
{
    const STORE_DEFAULT_NUMBER = '1';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var OAuthFactory
     */
    protected $oauth;

    /**
     * @var UserFactory
     */
    protected $user;

    /**
     * @var Client
     */
    protected $client;

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
    }

    /**
     * @return OAuthFactory
     */
    public function oauth()
    {
        if (null === $this->oauth) {
            $this->oauth = new OAuthFactory($this->container, $this);
        }
        return $this->oauth;
    }

    /**
     * @return UserFactory
     */
    public function user()
    {
        if (null === $this->user) {
            $this->user = new UserFactory($this->container, $this);
        }
        return $this->user;
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
            $manager = $this->user()->getUser($username, UserFactory::USER_DEFAULT_PASSWORD, User::ROLE_STORE_MANAGER);
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
        return $this->oauth()->auth($storeManager);
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
            $manager = $this->user()->getUser(
                $username,
                UserFactory::USER_DEFAULT_PASSWORD,
                User::ROLE_DEPARTMENT_MANAGER
            );
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
        $departmentManager = $this->getDepartmentManager($storeId);
        return $this->oauth()->auth($departmentManager);
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

        $accessToken = $this->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
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
        $accessToken = $this->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
                ->findOrCreateByStoreIdProductId($storeId, $productId)
                ->id;
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
        $this->getDocumentManager()->flush();
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
        $saleModel->createdDate = new DateTimestamp($createdDate);
        $saleModel->store = $this->getDocumentManager()->getReference(Store::getClassName(), $storeId);
        $saleModel->hash = md5(rand() . $storeId);
        $saleModel->sumTotal = $this->getNumericFactory()->createMoney($sumTotal);

        $this->getDocumentManager()->persist($saleModel);

        return $saleModel;
    }

    /**
     * @param Sale $sale
     */
    public function deleteSale(Sale $sale)
    {
        $this->getReceiptRepository()->rollbackByHash($sale->hash);
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

        $this->getDocumentManager()->persist($saleProduct);

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

    /**
     * @return ReceiptRepository
     */
    protected function getReceiptRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.receipt');
    }

    /**
     * @param string $name
     * @return Supplier
     */
    public function createSupplier($name = 'default')
    {
        $supplier = new Supplier();
        $supplier->name = $name;
        $this->getDocumentManager()->persist($supplier);

        return $supplier;
    }

    /**
     * @param string $name
     * @param string $url
     * @param int $size
     * @return File
     */
    public function createFile($name = 'default.txt', $url = 'http://cdn.lighthouse.pro/123', $size = 123)
    {
        $file = new File();
        $file->name = $name;
        $file->url = $url;
        $file->size = $size;
        $this->getDocumentManager()->persist($file);

        return $file;
    }
}
