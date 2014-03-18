<?php

namespace Lighthouse\CoreBundle\Test;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\Client\Client;
use Lighthouse\CoreBundle\Test\Factory\Factory;
use PHPUnit_Framework_ExpectationFailedException;

/**
 * @codeCoverageIgnore
 */
class WebTestCase extends ContainerAwareTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var User
     */
    protected $departmentManager;

    /**
     * @var string
     */
    protected $storeId;

    /**
     * @var Factory
     */
    protected $factory;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->clearMongoDb();
        $this->factory = new Factory($this->getContainer());
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->storeId = null;
        $this->departmentManager = null;
        $this->factory = null;
        $this->client = null;
    }

    /**
     *
     */
    protected function setUpStoreDepartmentManager()
    {
        $this->storeId = $this->factory->store()->getStoreId();
        $this->departmentManager = $this->factory->store()->getDepartmentManager($this->storeId);
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
        return $this->client->jsonRequest($jsonRequest, $accessToken);
    }

    /**
     * @param array $modifiedData
     * @param string $storeId
     * @param User $departmentManager
     * @return mixed
     */
    protected function createInvoice(array $modifiedData, $storeId, User $departmentManager = null)
    {
        $departmentManager = ($departmentManager) ?: $this->factory->store()->getDepartmentManager($storeId);
        $accessToken = $this->factory->oauth()->auth($departmentManager);

        $invoiceData = $modifiedData + array(
            'sku' => 'sku232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
            'includesVAT' => true,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/invoices',
            $invoiceData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param array $modifiedData
     * @param string $invoiceId
     * @param string $storeId
     * @param User $departmentManager
     * @return mixed
     */
    protected function editInvoice(array $modifiedData, $invoiceId, $storeId, User $departmentManager = null)
    {
        $departmentManager = ($departmentManager) ?: $this->factory->store()->getDepartmentManager($storeId);
        $accessToken = $this->factory->oauth()->auth($departmentManager);

        $invoiceData = $modifiedData + array(
            'sku' => 'sku232',
            'supplier' => 'ООО "Поставщик"',
            'acceptanceDate' => '2013-03-18 12:56',
            'accepter' => 'Приемных Н.П.',
            'legalEntity' => 'ООО "Магазин"',
            'supplierInvoiceSku' => '1248373',
            'supplierInvoiceDate' => '17.03.2013',
            'includesVAT' => true,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId,
            $invoiceData
        );

        $this->assertResponseCode(200);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $invoiceId
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $storeId
     * @param User $manager
     * @return string
     */
    public function createInvoiceProduct(
        $invoiceId,
        $productId,
        $quantity,
        $price,
        $storeId = null,
        $manager = null
    ) {
        $manager = ($manager) ?: $this->departmentManager;
        $storeId = ($storeId) ?: $this->storeId;
        $manager = ($manager) ?: $this->factory->store()->getDepartmentManager($storeId);

        $accessToken = $this->factory->oauth()->auth($manager);

        $invoiceProductData = array(
            'product' => $productId,
            'quantity' => $quantity,
            'priceEntered' => $price,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId . '/products',
            $invoiceProductData
        );

        $this->assertResponseCode(201);

        return $postResponse['id'];
    }

    /**
     * @param string $invoiceProductId
     * @param string $invoiceId
     * @param string $productId
     * @param float $quantity
     * @param float $price
     * @param string $storeId
     * @param User $manager
     * @return string
     */
    public function editInvoiceProduct(
        $invoiceProductId,
        $invoiceId,
        $productId,
        $quantity,
        $price,
        $storeId = null,
        $manager = null
    ) {
        $manager = ($manager) ?: $this->departmentManager;
        $storeId = ($storeId) ?: $this->storeId;
        $manager = ($manager) ?: $this->factory->store()->getDepartmentManager($storeId);

        $accessToken = $this->factory->oauth()->auth($manager);

        $invoiceProductData = array(
            'product' => $productId,
            'quantity' => $quantity,
            'priceEntered' => $price,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId,
            $invoiceProductData
        );

        $this->assertResponseCode(200);

        return $postResponse['id'];
    }

    public function deleteInvoiceProduct(
        $invoiceProductId,
        $invoiceId,
        $storeId = null,
        $manager = null
    ) {
        $manager = ($manager) ?: $this->departmentManager;
        $storeId = ($storeId) ?: $this->storeId;
        $manager = ($manager) ?: $this->factory->store()->getDepartmentManager($storeId);

        $accessToken = $this->factory->oauth()->auth($manager);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/stores/' . $storeId . '/invoices/' . $invoiceId . '/products/' . $invoiceProductId
        );

        $this->assertResponseCode(204);

        return $postResponse['id'];
    }

    /**
     * @param $productId
     * @param $sellingPrice
     * @param $quantity
     * @param string $date
     * @return mixed
     */
    public function createSaleWithProduct($productId, $sellingPrice, $quantity, $date = 'now')
    {
        $saleProductData = array(
            'product' => $productId,
            'sellingPrice' => $sellingPrice,
            'quantity' => $quantity,
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_DEPARTMENT_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/sales',
            array(
                'createdDate' => date('c', strtotime($date)),
                'products' => array($saleProductData),
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
            $subCategoryId = $this->factory->catalog()->getSubCategory()->id;
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

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
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
     * @param array $skus
     * @param bool $unique
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return array
     */
    protected function createProductsBySku(array $skus, $unique = false)
    {
        if ($unique) {
            $skus = array_unique($skus);
        }
        $products = array();
        $failedSkus = array();
        foreach ($skus as $sku) {
            try {
                $products[$sku] = $this->createProduct(array('sku' => $sku));
            } catch (\PHPUnit_Framework_AssertionFailedError $e) {
                $failedSkus[] = $sku;
            }
        }
        if (count($failedSkus) > 0) {
            throw new \PHPUnit_Framework_AssertionFailedError(
                sprintf('Failed to create products with following skus: %s', implode(', ', $failedSkus))
            );
        }
        return $products;
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
                'priceEntered' => 11.12,
                'productAmount' => 10,
            ),
            array(
                'product' => $productId,
                'quantity' => 5,
                'priceEntered' => 12.76,
                'productAmount' => 15,
            ),
            array(
                'product' => $productId,
                'quantity' => 1,
                'priceEntered' => 5.99,
                'productAmount' => 16,
            ),
        );

        $accessToken = $this->factory->oauth()->auth($this->departmentManager);

        foreach ($productsData as $i => $row) {

            $invoiceProductData = array(
                'quantity' => $row['quantity'],
                'priceEntered' => $row['priceEntered'],
                'product' => $row['product'],
            );

            $response = $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products.json',
                $invoiceProductData
            );

            $this->assertResponseCode(201);
            $productsData[$i]['id'] = $response['id'];
        }

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/invoices/' . $invoiceId . '/products'
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
     * @param string $date timestamp
     * @param string $storeId
     * @param User $departmentManager
     * @return string
     */
    protected function createWriteOff($number, $date, $storeId, User $departmentManager = null)
    {
        $departmentManager = ($departmentManager) ?: $this->factory->store()->getDepartmentManager($storeId);
        $accessToken = $this->factory->oauth()->auth($departmentManager);

        $date = $date ? : date('c', strtotime('-1 day'));

        $postData = array(
            'number' => $number,
            'date' => $date,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $storeId . '/writeoffs',
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
     * @param string $storeId
     * @param User $manager
     * @return string
     */
    protected function createWriteOffProduct(
        $writeOffId,
        $productId,
        $quantity = 10,
        $price = 5.99,
        $cause = 'Порча',
        $storeId = null,
        User $manager = null
    ) {
        $manager = ($manager) ?: $this->departmentManager;
        $storeId = ($storeId) ?: $this->storeId;
        $price = ($price) ?: 5.99;
        $quantity = ($quantity) ?: 10;
        $cause = ($cause) ?: 'Порча';

        $accessToken = $this->factory->oauth()->auth($manager);

        $postData = array(
            'product' => $productId,
            'price' => $price,
            'quantity' => $quantity,
            'cause' => $cause,
        );

        $request = new JsonRequest(
            '/api/1/stores/' . $storeId . '/writeoffs/' . $writeOffId . '/products',
            'POST',
            $postData
        );
        $postResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param array $catalog
     * @return array
     */
    protected function createCatalog(array $catalog)
    {
        $catalogIds = array();
        foreach ($catalog as $groupName => $categories) {
            $groupId = $this->factory->catalog()->createGroup($groupName)->id;
            $catalogIds[$groupName] = $groupId;
            foreach ($categories as $categoryName => $subCategories) {
                $categoryId = $this->factory->catalog()->createCategory($groupId, $categoryName)->id;
                $catalogIds[$categoryName] = $categoryId;
                foreach ($subCategories as $subCategoryName => $void) {
                    $subCategoryId = $this->factory->catalog()->createSubCategory($categoryId, $subCategoryName)->id;
                    $catalogIds[$subCategoryName] = $subCategoryId;
                }
            }
        }
        return $catalogIds;
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
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $request = new JsonRequest('/api/1/products/' . $productId);
        $request->setAccessToken($accessToken);

        $productJson = $this->jsonRequest($request);

        $this->assertResponseCode(200);

        $this->performJsonAssertions($productJson, $assertions);
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param array  $assertions
     * @param string $message
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    protected function assertStoreProduct($storeId, $productId, array $assertions, $message = '')
    {
        $accessToken = $this->factory->oauth()->authAsStoreManager($storeId);

        $storeProductJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $productId
        );

        $this->assertResponseCode(200);

        try {
            $this->performJsonAssertions($storeProductJson, $assertions);
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            $message.= ($message) ? '. ' . $e->getMessage() : $e->getMessage();
            throw new PHPUnit_Framework_ExpectationFailedException(
                $message,
                $e->getComparisonFailure(),
                $e->getPrevious()
            );
        }
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param int $inventory
     * @param float $lastPurchasePrice
     */
    protected function assertStoreProductTotals($storeId, $productId, $inventory, $lastPurchasePrice = null)
    {
        $assertions = array(
            'inventory' => $inventory,
            'lastPurchasePrice' => $lastPurchasePrice
        );

        $this->assertStoreProduct($storeId, $productId, $assertions);
    }

    /**
     * @deprecated
     * @param string $name
     * @return string
     */
    protected function createGroup($name = 'Продовольственные товары')
    {
        return $this->factory->catalog()->createGroup($name)->id;
    }

    /**
     * @deprecated
     * @param string $groupId
     * @param string $name
     * @return string
     */
    protected function createCategory($groupId = null, $name = 'Винно-водочные изделия')
    {
        return $this->factory->catalog()->createCategory($groupId, $name)->id;
    }

    /**
     * @deprecated
     * @param string $categoryId
     * @param string $name
     * @return string
     */
    protected function createSubCategory($categoryId = null, $name = 'Водка')
    {
        return $this->factory->catalog()->createSubCategory($categoryId, $name)->id;
    }

    public function createDepartment(
        $storeId = null,
        $number = 'отдел_42',
        $name = 'название отдела 42',
        $ifNotExists = true
    ) {
        if ($storeId == null) {
            $storeId = $this->factory->store()->getStoreId();
        }

        $departmentData = array(
            'number' => $number,
            'name' => $name,
            'store' => $storeId,
        );

        $accessToken = $this->factory->oauth()->authAsRole("ROLE_COMMERCIAL_MANAGER");

        if ($ifNotExists) {
            $postResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/stores/' . $storeId . '/departments'
            );

            if (is_array($postResponse)) {
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
            $departmentData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $response);
        Assert::assertJsonPathEquals($departmentData['number'], 'number', $response);
        Assert::assertJsonPathEquals($departmentData['name'], 'name', $response);

        return $response['id'];
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param array $productData
     */
    public function updateStoreProduct(
        $storeId,
        $productId,
        array $productData = array()
    ) {
        $accessToken = $this->factory->oauth()->authAsStoreManager($storeId);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $storeId . '/products/' . $productId,
            $productData
        );

        $this->assertResponseCode(200);
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

    /**
     * @param string $name
     * @param string $value
     * @return mixed
     */
    public function createConfig($name = 'test-config', $value = 'test-config-value')
    {
        $configData = array(
            'name' => $name,
            'value' => $value,
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            "POST",
            "/api/1/configs",
            $configData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($name, 'name', $postResponse);
        Assert::assertJsonPathEquals($value, 'value', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }

    /**
     * @param string $configId
     * @param string $name
     * @param string $value
     * @return string
     */
    public function updateConfig($configId, $name = 'test-config', $value = 'test-config-value')
    {
        $configData = array(
            'name' => $name,
            'value' => $value,
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            "PUT",
            "/api/1/configs/" . $configId,
            $configData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($name, 'name', $postResponse);
        if ($value !== '') {
            Assert::assertJsonPathEquals($value, 'value', $postResponse);
        }
        Assert::assertJsonPathEquals($configId, 'id', $postResponse);

        return $postResponse['id'];
    }
}
