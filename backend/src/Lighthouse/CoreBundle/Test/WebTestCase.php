<?php

namespace Lighthouse\CoreBundle\Test;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\Auth\Client as AuthClient;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Document\User\UserRepository;
use Lighthouse\CoreBundle\Security\User\UserProvider;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Util\JsonPath;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use AppKernel;

/**
 * @codeCoverageIgnore
 */
class WebTestCase extends ContainerAwareTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected $oauthClient;

    /**
     * @var User[]
     */
    protected $oauthUsers = array();

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @param \stdClass|string $token
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $parameters
     * @param array $server
     * @param bool $changeHistory
     * @return array
     * @throws \Exception
     */
    protected function clientJsonRequest(
        $token,
        $method,
        $uri,
        $data = null,
        array $parameters = array(),
        array $server = array(),
        $changeHistory = false
    ) {
        $request = new JsonRequest($uri, $method);

        $request->parameters = $parameters;
        $request->server = $server;
        $request->changeHistory = $changeHistory;

        if ($token) {
            $request->setAccessToken($token);
        }

        $request->setJsonData($data);
        $request->setJsonHeaders();

        return $this->jsonRequest($request);
    }

    /**
     * @param JsonRequest $jsonRequest
     * @param \stdClass|string $accessToken
     * @return array
     */
    protected function jsonRequest(JsonRequest $jsonRequest, $accessToken = null)
    {
        if (null !== $accessToken) {
            $jsonRequest->setAccessToken($accessToken);
        }

        $this->client->request(
            $jsonRequest->method,
            $jsonRequest->uri,
            $jsonRequest->parameters,
            $jsonRequest->files,
            $jsonRequest->server,
            $jsonRequest->content,
            $jsonRequest->changeHistory
        );

        return $this->parseJsonResponse($this->client);
    }

    /**
     * @param array $modifiedData
     * @return string
     */
    protected function createInvoice(array $modifiedData = array())
    {
        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $invoiceData = $modifiedData + array(
            'sku' => 'sdfwfsf232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $invoiceId
     * @param string $productId
     * @param int $quantity
     * @param float $price
     * @return string
     */
    public function createInvoiceProduct($invoiceId, $productId, $quantity, $price)
    {
        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $invoiceProductData = array(
            'product' => $productId,
            'quantity' => $quantity,
            'price' => $price
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/invoices/' . $invoiceId . '/products.json',
            $invoiceProductData
        );

        $this->assertResponseCode(201);

        return $postResponse['id'];
    }

    public function createPurchaseWithProduct($productId, $sellingPrice, $quantity, $date = 'now')
    {
        $purchaseProductData = array(
            'product' => $productId,
            'sellingPrice' => $sellingPrice,
            'quantity' => $quantity,
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/purchases',
            array(
                'createdDate' => date('c', strtotime($date)),
                'products' => array($purchaseProductData),
            )
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string|array $extra
     * @param null|string $subCategoryId
     * @param bool|string $putProductId string id of product to be updated
     * @return mixed
     */
    protected function createProduct($extra = '', $subCategoryId = null, $putProductId = false)
    {
        if ($subCategoryId == null) {
            $subCategoryId = $this->createSubCategory();
        }

        $productData = array(
            'name' => 'Кефир "Веселый Молочник" 1% 950гр',
            'units' => 'gr',
            'barcode' => '4607025392408',
            'purchasePrice' => 3048,
            'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
            'vat' => 10,
            'vendor' => 'Вимм-Билль-Данн',
            'vendorCountry' => 'Россия',
            'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
            'subCategory' => $subCategoryId,
        );

        if (is_array($extra)) {
            $productData = $extra + $productData;
        } else {
            $productData['name'].= $extra;
            $productData['sku'].= $extra;
        }

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $method = ($putProductId) ? 'PUT' : 'POST';
        $url = '/api/1/products' . (($putProductId) ? '/' . $putProductId : '');
        $request = new JsonRequest($url, $method, $productData);
        $postResponse = $this->jsonRequest($request, $accessToken);

        $responseCode = ($putProductId) ? 200 : 201;
        $this->assertResponseCode($responseCode);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $productId
     * @param array $data
     */
    protected function updateProduct($productId, array $data)
    {
        $this->createProduct($data, null, $productId);
    }

    /**
     * @param $productId
     * @param $invoiceId
     * @return array
     */
    protected function createInvoiceProducts($productId, $invoiceId)
    {
        $productsData = array(
            array(
                'product' => $productId,
                'quantity' => 10,
                'price' => 11.12,
                'productAmount' => 10,
            ),
            array(
                'product' => $productId,
                'quantity' => 5,
                'price' => 12.76,
                'productAmount' => 15,
            ),
            array(
                'product' => $productId,
                'quantity' => 1,
                'price' => 5.99,
                'productAmount' => 16,
            ),
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        foreach ($productsData as $i => $row) {

            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'product' => $row['product'],
            );

            $response = $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/invoices/' . $invoiceId . '/products.json',
                $invoiceProductData
            );

            $this->assertResponseCode(201);
            $productsData[$i]['id'] = $response['id'];
        }

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/invoices/' . $invoiceId . '/products.json'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, "*.id", $getResponse);

        foreach ($productsData as $productData) {
            Assert::assertJsonPathEquals($productData['id'], '*.id', $getResponse);
        }

        return $productsData;
    }

    /**
     * @param string $number
     * @param int $date timestamp
     * @return mixed
     */
    protected function createWriteOff($number = '431-6782', $date = null)
    {
        $date = $date ? : date('c', strtotime('-1 day'));

        $postData = array(
            'number' => $number,
            'date' => $date,
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/writeoffs.json',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $writeOffId
     * @param string $productId
     * @param float $price
     * @param int $quantity
     * @param string $cause
     * @return string
     */
    protected function createWriteOffProduct($writeOffId, $productId, $price = 5.99, $quantity = 10, $cause = 'Порча')
    {
        $postData = array(
            'product' => $productId,
            'price' => $price,
            'quantity' => $quantity,
            'cause' => $cause,
        );

        $accessToken = $this->authAsRole('ROLE_DEPARTMENT_MANAGER');
        $request = new JsonRequest('/api/1/writeoffs/' . $writeOffId . '/products', 'POST', $postData);
        $postResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $name
     * @param bool $ifNotExists
     * @param null $retailMarkupMin
     * @param null $retailMarkupMax
     * @return string
     */
    protected function createGroup(
        $name = 'Продовольственные товары',
        $ifNotExists = true,
        $retailMarkupMin = null,
        $retailMarkupMax = null
    ) {
        $postData = array(
            'name' => $name,
            'retailMarkupMin' => $retailMarkupMin,
            'retailMarkupMax' => $retailMarkupMax,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        if ($ifNotExists) {
            $postResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/groups.json'
            );

            if (count($postResponse)) {
                foreach ($postResponse as $value) {
                    if ($value['name'] == $name) {
                        return $value['id'];
                    }
                }
            }
        }

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups.json',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param mixed $json
     * @param array $assertions
     * @param bool  $contains
     */
    protected function performJsonAssertions($json, array $assertions, $contains = false)
    {
        foreach ($assertions as $path => $expected) {
            if (null === $expected) {
                Assert::assertNotJsonHasPath($path, $json);
            } elseif ($contains) {
                Assert::assertJsonPathContains($expected, $path, $json);
            } else {
                Assert::assertJsonPathEquals($expected, $path, $json);
            }
        }
    }

    /**
     * @param string $productId
     * @param array $assertions
     */
    protected function assertProduct($productId, array $assertions)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $request = new JsonRequest('/api/1/products/' . $productId);
        $request->setAccessToken($accessToken);

        $productJson = $this->jsonRequest($request);

        $this->assertResponseCode(200);

        $this->performJsonAssertions($productJson, $assertions);
    }


    /**
     * @param string $productId
     * @param int $amount
     * @param float $lastPurchasePrice
     */
    protected function assertProductTotals($productId, $amount, $lastPurchasePrice)
    {
        $assertions = array(
            'amount' => $amount,
            'lastPurchasePrice' => $lastPurchasePrice,
        );

        $this->assertProduct($productId, $assertions);
    }

    /**
     * @param string $groupId
     * @param string $name
     * @return string
     */
    protected function createCategory($groupId = null, $name = 'Винно-водочные изделия', $ifNotExists = true)
    {
        if ($groupId == null) {
            $groupId = $this->createGroup();
        }
        $categoryData = array(
            'name' => $name,
            'group' => $groupId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        if ($ifNotExists) {
            $postResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/groups/'. $groupId .'/categories'
            );

            if (count($postResponse)) {
                foreach ($postResponse as $value) {
                    if ($value['name'] == $name) {
                        return $value['id'];
                    }
                }
            }
        }

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }


    /**
     * @param string $categoryId
     * @param string $name
     * @return string
     */
    protected function createSubCategory($categoryId = null, $name = 'Водка', $ifNotExists = true)
    {
        if ($categoryId == null) {
            $categoryId = $this->createCategory();
        }
        $subCategoryData = array(
            'name' => $name,
            'category' => $categoryId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        if ($ifNotExists) {
            $postResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/categories/'. $categoryId .'/subcategories'
            );

            $this->assertResponseCode(200);

            if (count($postResponse)) {
                foreach ($postResponse as $value) {
                    if ($value['name'] == $name) {
                        return $value['id'];
                    }
                }
            }
        }

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $number
     * @param string $address
     * @param string $contacts
     * @param bool $ifNotExists
     * @return string
     */
    public function createStore(
        $number = 'номер_42',
        $address = 'адрес 42',
        $contacts = 'телефон 42',
        $ifNotExists = false
    ) {
        $storeData = array(
            'number' => $number,
            'address' => $address,
            'contacts' => $contacts,
        );

        $accessToken = $this->authAsRole("ROLE_COMMERCIAL_MANAGER");

        if ($ifNotExists) {
            $postResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/stores'
            );

            if (count($postResponse)) {
                foreach ($postResponse as $value) {
                    if (is_array($value) && array_key_exists('number', $value) && $value['number'] == $number) {
                        return $value['id'];
                    }
                }
            }
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores',
            $storeData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        foreach ($storeData as $name => $value) {
            Assert::assertJsonPathEquals($value, $name, $response);
        }

        return $response['id'];
    }

    public function createDepartment(
        $storeId = null,
        $number = 'отдел_42',
        $name = 'название отдела 42',
        $ifNotExists = true
    ) {
        if ($storeId == null) {
            $storeId = $this->createStore();
        }

        $storeData = array(
            'number' => $number,
            'name' => $name,
            'store' => $storeId,
        );

        $accessToken = $this->authAsRole("ROLE_COMMERCIAL_MANAGER");

        if ($ifNotExists) {
            $postResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/stores/' . $storeId . '/departments'
            );

            if (count($postResponse)) {
                foreach ($postResponse as $value) {
                    if (is_array($value) && array_key_exists('number', $value) && $value['number'] == $number) {
                        return $value['id'];
                    }
                }
            }
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/departments',
            $storeData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals($storeData['number'], 'number', $response);
        Assert::assertJsonPathEquals($storeData['name'], 'name', $response);

        return $response['id'];
    }

    /**
     * @param string $storeId
     * @param string|array $userIds
     */
    public function linkStoreManagers($storeId, $userIds)
    {
        $userIds = (array) $userIds;

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        foreach ($userIds as $userId) {
            $request->addLinkHeader('http://localhost/api/1/users/' . $userId, 'managers');
        }

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);
    }


    /**
     * @param string $secret
     * @return AuthClient
     */
    protected function createAuthClient($secret = 'secret')
    {
        $client = new AuthClient();
        $client->setSecret($secret);

        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $dm->persist($client);
        $dm->flush();

        return $client;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $position
     * @return User
     */
    protected function createUser(
        $username = 'admin',
        $password = 'password',
        $role = 'ROLE_ADMINISTRATOR',
        $name = 'Админ Админыч',
        $position = 'Администратор'
    ) {
        /* @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get('lighthouse.core.document.repository.user');
        /* @var UserProvider $userProvider */
        $userProvider = $this->getContainer()->get('lighthouse.core.user.provider');

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
     * @param string $role
     * @return \stdClass accessToken
     */
    protected function authAsRole($role)
    {
        $user = $this->getRoleUser($role);
        return $this->auth($user);
    }

    /**
     * @param string $role
     * @return User
     */
    protected function getRoleUser($role)
    {
        if (!isset($this->oauthUsers[$role])) {
            $this->oauthUsers[$role] = $this->createUser($role, 'password', $role, $role, $role);
        }

        return $this->oauthUsers[$role];
    }

    /**
     * @param User $oauthUser
     * @param string $password
     * @param AuthClient $oauthClient
     * @return \stdClass access token
     */
    protected function auth(User $oauthUser = null, $password = 'password', AuthClient $oauthClient = null)
    {
        if (!$oauthClient) {
            if (!$this->oauthClient) {
                $this->oauthClient = $this->createAuthClient();
            }
            $oauthClient = $this->oauthClient;
        }

        if (!$oauthUser) {
            $oauthUser = $this->getRoleUser('ROLE_ADMINISTRATOR');
        }

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

        $response = $this->client->getResponse()->getContent();
        $json = json_decode($response);

        return $json;
    }

    /**
     * @param Client $client
     * @return mixed
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    protected function parseJsonResponse(Client $client)
    {
        $content = $client->getResponse()->getContent();
        $json = json_decode($content, true);

        if (0 != json_last_error()) {
            throw new \PHPUnit_Framework_AssertionFailedError(
                sprintf('Failed asserting that response body is json. Response given: %s', $content)
            );
        }

        return $json;
    }

    /**
     * @param string $format
     * @return string
     */
    protected function getNowDate($format = 'Y-m-d\\TH:')
    {
        return date($format);
    }

    /**
     * @param integer $expectedCode
     */
    public function assertResponseCode($expectedCode)
    {
        Assert::assertResponseCode($expectedCode, $this->client);
    }
}
