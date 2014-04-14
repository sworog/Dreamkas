<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Document\User\User;

class StoreProductControllerTest extends WebTestCase
{
    /**
     * @var User
     */
    protected $storeManager;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string
     */
    protected $storeId;

    protected function setUp()
    {
        parent::setUp();
        $this->initStoreProduct();
    }

    protected function initStoreProduct()
    {
        $this->storeManager = $this->factory->user()->getUser(
            'Василий Петрович Краузе',
            'password',
            User::ROLE_STORE_MANAGER
        );

        $this->productId = $this->createProduct();
        $this->storeId = $this->factory->store()->getStoreId();

        $this->factory->store()->linkStoreManagers($this->storeManager->id, $this->storeId);
    }

    public function testGetActionNoStoreProductCreated()
    {
        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/' . $this->productId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($this->productId, 'product.id', $getResponse);
        Assert::assertJsonPathEquals($this->storeId, 'store.id', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
    }

    public function testGetActionProductDoesNotExist()
    {
        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/aaaa'
        );

        $this->assertResponseCode(404);
        Assert::assertJsonPathContains('No route found', 'message', $getResponse, false);
    }

    public function testGetActionProductExistsStoreNotExists()
    {
        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/aaa/products/' . $this->productId
        );

        $this->assertResponseCode(404);
        Assert::assertJsonPathContains('No route found', 'message', $getResponse, false);
    }

    /**
     * @param array $data
     * @param $expectedCode
     * @param array $assertions
     * @param array $productData
     * @dataProvider retailPriceValidateDataProvider
     */
    public function testPutActionRetailPriceValidate(
        $expectedCode,
        array $data,
        array $assertions = array(),
        array $productData = array()
    ) {
        $productData += array(
            'sku' => 'Водка селедка',
            'purchasePrice' => 30.48,
            'retailPriceMin' => 31,
            'retailPriceMax' => 40,
            'retailPricePreference' => 'retailPrice',
        );

        $productId = $this->createProduct($productData);

        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $putData = $data;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/products/' . $productId,
            $putData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions);
    }

    /**
     * @return array
     */
    public function retailPriceValidateDataProvider()
    {
        return array(
            'valid price' => array(
                200,
                array(
                    'retailPrice' => 35,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'id' => null,
                    'retailPrice' => 35,
                    'retailMarkup' => 14.83
                )
            ),
            'valid price equals min' => array(
                200,
                array(
                    'retailPrice' => 31,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'id' => null,
                    'retailPrice' => 31,
                    'retailMarkup' => 1.71
                )
            ),
            'valid price equals max' => array(
                200,
                array(
                    'retailPrice' => 40,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'id' => null,
                    'retailPrice' => 40,
                    'retailMarkup' => 31.23
                )
            ),
            'invalid price less than min' => array(
                400,
                array(
                    'retailPrice' => 30.40,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена должна быть больше или равна 31.00',
                    'children.retailPrice.errors.1' => null,
                    'children.retailMarkup.errors' => null,
                )
            ),
            'invalid price less than min and purchasePrice' => array(
                400,
                array(
                    'retailPrice' => 30,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена должна быть больше или равна 31.00',
                    'children.retailPrice.errors.1' => null,
                    'children.retailMarkup.errors' => null,
                ),
            ),
            'invalid price more than max' => array(
                400,
                array(
                    'retailPrice' => 41,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена должна быть меньше или равна 40.00',
                    'children.retailPrice.errors.1' => null,
                    'children.retailMarkup.errors' => null,
                )
            ),
            'valid markup' => array(
                200,
                array(
                    'retailMarkup' => 14.83,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'id' => null,
                    'retailPrice' => 35,
                    'retailMarkup' => 14.83
                )
            ),
            'valid markup equals min' => array(
                200,
                array(
                    'retailMarkup' => 1.71,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'id' => null,
                    'retailPrice' => 31,
                    'retailMarkup' => 1.71
                )
            ),
            'valid markup equals max' => array(
                200,
                array(
                    'retailMarkup' => 31.23,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'id' => null,
                    'retailPrice' => 40,
                    'retailMarkup' => 31.23
                )
            ),
            'invalid markup less than min' => array(
                400,
                array(
                    'retailMarkup' => 1,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше или равна 1.71%',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                )
            ),
            'invalid markup mare than max' => array(
                400,
                array(
                    'retailMarkup' => 32,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть меньше или равна 31.23%',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                )
            ),
            'invalid markup less than min just a little' => array(
                400,
                array(
                    'retailMarkup' => 1.7,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше или равна 1.71%',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                )
            ),
            'invalid markup more than max just a little' => array(
                400,
                array(
                    'retailMarkup' => 31.24,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть меньше или равна 31.23%',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                )
            ),
            'invalid price string' => array(
                400,
                array(
                    'retailPrice' => 'aaaa',
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Значение должно быть числом',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailMarkup.errors' => null,
                )
            ),
            'invalid price 3 digits after comma' => array(
                400,
                array(
                    'retailPrice' => 35.555,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailMarkup.errors' => null,
                )
            ),
            'invalid markup string' => array(
                400,
                array(
                    'retailMarkup' => 'aaaa',
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть числом',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                )
            ),
            'invalid markup 3 digits after comma' => array(
                400,
                array(
                    'retailMarkup' => 2.234,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка не должна содержать больше 2 цифр после запятой',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                )
            ),
            'valid subcategory is not exposed' => array(
                200,
                array(
                ),
                array(
                    'id' => null,
                    'subCategory' => null,
                )
            ),
            'invalid retail price entered when no min max product markup provided' => array(
                400,
                array(
                    'retailPrice' => 30.48,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0'
                    =>
                    'Нельзя установить цену продажи, если не установлен диапозон цены продажи',
                    'children.retailPrice.errors.1' => null,
                    'children.retailMarkup.errors'  => null,
                ),
                array(
                    'purchasePrice' => 30.48,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'invalid retail markup entered when no min max product markup provided' => array(
                400,
                array(
                    'retailMarkup' => 10.11,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0'
                    =>
                    'Нельзя установить наценку, если не установлен диапозон наценки',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                ),
                array(
                    'purchasePrice' => 30.48,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'invalid retail markup entered preference not entered when no min max product markup provided' => array(
                400,
                array(
                    'retailMarkup' => 10.11,
                    'retailPricePreference' => null,
                ),
                array(
                    'children.retailMarkup.errors.0'
                    =>
                    'Нельзя установить наценку, если не установлен диапозон наценки',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                ),
                array(
                    'purchasePrice' => 30.48,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
        );
    }

    public function testStoreManagerAccessHasNoStore()
    {
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_STORE_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/' . $this->productId
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    public function testStoreManagerAccessHasStore()
    {
        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/' . $this->productId
        );

        $this->assertResponseCode(200);
    }

    public function testDepartmentManagerAccessHasNoStore()
    {
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_DEPARTMENT_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/' . $this->productId
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    public function testDepartmentManagerAccessHasStore()
    {
        $departmentManager = $this->factory->user()->getUser(
            'Василиса Петровна Бздых',
            'password',
            User::ROLE_DEPARTMENT_MANAGER
        );
        $this->factory->store()->linkDepartmentManagers($departmentManager->id, $this->storeId);

        $accessToken = $this->factory->oauth()->auth($departmentManager, 'password');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/' . $this->productId
        );

        $this->assertResponseCode(200);
    }

    public function testGetStoreSubCategoryProductsStoreManagerHasStore()
    {
        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $subCategoryId = $this->createSubCategory(null, 'Вино сухое');

        $productId1 = $this->createProduct('1', $subCategoryId);
        $productId2 = $this->createProduct('2', $subCategoryId);
        $productId3 = $this->createProduct('3', $subCategoryId);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/subcategories/'  . $subCategoryId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.product', $getResponse);
        Assert::assertJsonPathEquals($productId1, '*.product.id', $getResponse, 1);
        Assert::assertJsonPathEquals($productId2, '*.product.id', $getResponse, 1);
        Assert::assertJsonPathEquals($productId3, '*.product.id', $getResponse, 1);
    }

    public function testGetStoreSubCategoryProductsHasNoSubCategoryAndStore()
    {
        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $subCategoryId = $this->createSubCategory(null, 'Вино сухое');

        $this->createProduct('1', $subCategoryId);
        $this->createProduct('2', $subCategoryId);
        $this->createProduct('3', $subCategoryId);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/subcategories/'  . $subCategoryId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.product', $getResponse);
        Assert::assertJsonPathCount(0, '*.product.subCategory', $getResponse);
        Assert::assertJsonPathCount(0, '*.store', $getResponse);
    }

    /**
     * @param $expectedCode
     * @param $productRounding
     * @param array $data
     * @param array $assertions
     * @param array $productData
     *
     * @dataProvider roundingsProvider
     */
    public function testRoundings(
        $expectedCode,
        $productRounding,
        array $data,
        array $assertions,
        array $productData = array()
    ) {
        $productData += array(
            'sku' => 'Водка селедка',
            'purchasePrice' => 30.48,
            'retailPriceMin' => 31,
            'retailPriceMax' => 40,
            'retailPricePreference' => 'retailPrice',
            'rounding' => $productRounding,
        );

        $productId = $this->createProduct($productData);

        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $putData = $data + array('retailPricePreference' => 'retailPrice');

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/stores/' . $this->storeId . '/products/' . $productId,
            $putData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions);

        if (200 == $expectedCode) {
            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/stores/' . $this->storeId . '/products/' . $productId
            );

            $this->assertEquals($putResponse, $getResponse, 'PUT and GET responses should be same');
        }
    }

    /**
     * @return array
     */
    public function roundingsProvider()
    {
        return array(
            // roundings
            'valid nearest1 price set' => array(
                200,
                'nearest1',
                array(
                    'retailPrice' => '31.54',
                ),
                array(
                    'retailPrice' => '31.54',
                    'retailMarkup' => '3.48',
                    'roundedRetailPrice' => '31.54',
                    'product.rounding.name' => 'nearest1',
                )
            ),
            'valid nearest1 price set up' => array(
                200,
                'nearest1',
                array(
                    'retailPrice' => '31.55',
                ),
                array(
                    'retailPrice' => '31.55',
                    'retailMarkup' => '3.51',
                    'roundedRetailPrice' => '31.55',
                    'product.rounding.name' => 'nearest1',
                )
            ),
            'valid nearest10 price set' => array(
                200,
                'nearest10',
                array(
                    'retailPrice' => '31.54',
                ),
                array(
                    'retailPrice' => '31.54',
                    'retailMarkup' => '3.48',
                    'roundedRetailPrice' => '31.50',
                    'product.rounding.name' => 'nearest10',
                )
            ),
            'valid nearest10 price set up' => array(
                200,
                'nearest10',
                array(
                    'retailPrice' => '31.55',
                ),
                array(
                    'retailPrice' => '31.55',
                    'retailMarkup' => '3.51',
                    'roundedRetailPrice' => '31.60',
                    'product.rounding.name' => 'nearest10',
                )
            ),
            'valid nearest50 price set' => array(
                200,
                'nearest50',
                array(
                    'retailPrice' => '31.54',
                ),
                array(
                    'retailPrice' => '31.54',
                    'retailMarkup' => '3.48',
                    'roundedRetailPrice' => '31.50',
                    'product.rounding.name' => 'nearest50',
                )
            ),
            'valid nearest100 price set' => array(
                200,
                'nearest100',
                array(
                    'retailPrice' => '31.54',
                ),
                array(
                    'retailPrice' => '31.54',
                    'retailMarkup' => '3.48',
                    'roundedRetailPrice' => '32.00',
                    'product.rounding.name' => 'nearest100',
                )
            ),
            'valid nearest99 price set' => array(
                200,
                'nearest99',
                array(
                    'retailPrice' => '31.54',
                ),
                array(
                    'retailPrice' => '31.54',
                    'retailMarkup' => '3.48',
                    'roundedRetailPrice' => '31.99',
                    'product.rounding.name' => 'nearest99',
                )
            ),
            // 0 rounding
            'invalid price rounds to 0 with nearest10' => array(
                400,
                'nearest10',
                array(
                    'retailPrice' => 0.04,
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
                ),
                array(
                    'purchasePrice' => 0.02,
                    'retailPriceMin' => 0.03,
                )
            ),
            'invalid price rounds to 0 with nearest50' => array(
                400,
                'nearest50',
                array(
                    'retailPrice' => 0.24,
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
                ),
                array(
                    'purchasePrice' => 0.20,
                    'retailPriceMin' => 0.21,
                )
            ),
            'invalid price rounds to 0 with nearest100' => array(
                400,
                'nearest100',
                array(
                    'retailPrice' => 0.44,
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
                ),
                array(
                    'purchasePrice' => 0.40,
                    'retailPriceMin' => 0.41,
                )
            ),
            'invalid price rounds to 0 with nearest99' => array(
                400,
                'nearest100',
                array(
                    'retailPrice' => 0.44,
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
                ),
                array(
                    'purchasePrice' => 0.40,
                    'retailPriceMin' => 0.40,
                )
            )
        );
    }

    /**
     * @param array $productData
     * @param array $assertions
     *
     * @dataProvider notUpdatedStoreProductProvider
     */
    public function testNotUpdatedStoreProduct(array $productData, array $assertions)
    {
        $productData += array(
            'sku' => 'Водка селедка',
            'purchasePrice' => 30.48,
            'retailPriceMin' => 31,
            'retailPriceMax' => 40,
            'retailPricePreference' => 'retailPrice',
        );

        $productId = $this->createProduct($productData);

        $accessToken = $this->factory->oauth()->auth($this->storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/' . $productId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('id', $getResponse);
        Assert::assertJsonPathEquals($productId, 'product.id', $getResponse);
        Assert::assertJsonPathEquals($this->storeId, 'store.id', $getResponse);

        $this->performJsonAssertions($getResponse, $assertions);
    }

    /**
     * @return array
     */
    public function notUpdatedStoreProductProvider()
    {
        return array(
            'retail price provided' => array(
                array(
                    'retailPriceMin' => 31,
                    'retailPriceMax' => 40,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'retailPrice' => '40.00',
                    'retailMarkup' => '31.23'
                )
            ),
            'no retail price and price prefer' => array(
                array(
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'retailPrice' => null,
                    'retailMarkup' => null,
                    'roundedRetailPrice' => null,
                )
            ),
            'no retail markup and markup prefer' => array(
                array(
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'retailPrice' => null,
                    'retailMarkup' => null,
                    'roundedRetailPrice' => null
                )
            ),
        );
    }

    public function testGetStoreProductsAction()
    {
        $storeId1 = $this->storeId;
        $storeId2 = $this->factory->store()->getStoreId('2', '2', '2');

        $departmentManager1 = $this->factory->user()->getUser('dm1', 'password', 'ROLE_DEPARTMENT_MANAGER');
        $departmentManager2 = $this->factory->user()->getUser('dm2', 'password', 'ROLE_DEPARTMENT_MANAGER');

        $this->factory->store()->linkDepartmentManagers($departmentManager1->id, $storeId1);
        $this->factory->store()->linkDepartmentManagers($departmentManager2->id, $storeId2);

        $productId1 = $this->productId;
        $productId2 = $this->createProduct('2');
        $productId3 = $this->createProduct('3');

        $departmentAccessToken1 = $this->factory->oauth()->auth($departmentManager1);
        $departmentAccessToken2 = $this->factory->oauth()->auth($departmentManager2);

        $response = $this->clientJsonRequest(
            $departmentAccessToken1,
            'GET',
            '/api/1/stores/' . $storeId1 . '/products'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId1, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId2, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId3, '*.product.id', $response);

        Assert::assertJsonPathEquals(0, '*.inventory', $response);

        $response = $this->clientJsonRequest(
            $departmentAccessToken2,
            'GET',
            '/api/1/stores/' . $storeId2. '/products'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId1, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId2, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId3, '*.product.id', $response);

        Assert::assertJsonPathEquals(0, '*.inventory', $response, 3);


        $invoice1Store1Id = $this->createInvoice(array(), $storeId1);
        $this->createInvoiceProduct($invoice1Store1Id, $productId1, 1, 10.99, $storeId1);
        $this->createInvoiceProduct($invoice1Store1Id, $productId2, 2, 11.99, $storeId1);

        $response = $this->clientJsonRequest(
            $departmentAccessToken1,
            'GET',
            '/api/1/stores/' . $storeId1 . '/products'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId1, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId2, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId3, '*.product.id', $response);

        Assert::assertJsonPathEquals(1, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(2, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(0, '*.inventory', $response, 1);

        Assert::assertJsonPathEquals(10.99, '*.lastPurchasePrice', $response, 1);
        Assert::assertJsonPathEquals(11.99, '*.lastPurchasePrice', $response, 1);

        $invoice2Store1Id = $this->createInvoice(array(), $storeId1);
        $this->createInvoiceProduct($invoice2Store1Id, $productId1, 3, 9.99, $storeId1);
        $this->createInvoiceProduct($invoice2Store1Id, $productId2, 4, 12.99, $storeId1);

        $response = $this->clientJsonRequest(
            $departmentAccessToken1,
            'GET',
            '/api/1/stores/' . $storeId1 . '/products'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId1, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId2, '*.product.id', $response);
        Assert::assertJsonPathEquals($productId3, '*.product.id', $response);

        Assert::assertJsonPathEquals(4, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(6, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(0, '*.inventory', $response, 1);

        Assert::assertJsonPathEquals(9.99, '*.lastPurchasePrice', $response, 1);
        Assert::assertJsonPathEquals(12.99, '*.lastPurchasePrice', $response, 1);
    }

    public function testPurchasePriceTotalsAndAmountAreReturnedUsingGetSubcategoryProductsMethod()
    {
        $groupId = $this->createGroup('1');
        $categoryId = $this->createCategory($groupId, '1.1');
        $subCategoryId1 = $this->createSubCategory($categoryId, '1.1.1');
        $subCategoryId2 = $this->createSubCategory($categoryId, '1.1.2');
        $productId1 = $this->createProduct('1', $subCategoryId1);
        $productId2 = $this->createProduct('2', $subCategoryId2);
        $storeId = $this->factory->store()->getStoreId('666');
        $departmentManager = $this->factory->store()->getDepartmentManager($storeId);

        $invoiceId0 = $this->createInvoice(array('acceptanceDate' => date('c', strtotime('-31 days'))), $storeId);
        $this->createInvoiceProduct($invoiceId0, $productId1, 10, 23.33, $storeId);

        $invoiceId1 = $this->createInvoice(array('acceptanceDate' => date('c', strtotime('-3 days'))), $storeId);

        $this->createInvoiceProduct($invoiceId1, $productId1, 10, 26, $storeId);
        $this->createInvoiceProduct($invoiceId1, $productId2, 6, 34.67, $storeId);

        $invoiceId2 = $this->createInvoice(array('acceptanceDate' => date('c', strtotime('-2 days'))), $storeId);

        $this->createInvoiceProduct($invoiceId2, $productId1, 5, 29, $storeId);

        $invoiceId3 = $this->createInvoice(array('acceptanceDate' => date('c', strtotime('-1 days'))), $storeId);

        $this->createInvoiceProduct($invoiceId3, $productId1, 10, 31, $storeId);

        /* @var $averagePriceService StoreProductMetricsCalculator */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.product.metrics_calculator');
        $averagePriceService->recalculateAveragePrice();

        $accessToken = $this->factory->oauth()->authAsStoreManager($storeId);

        $allProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.product.id', $allProductsResponse);
        Assert::assertJsonPathEquals('28.60', '*.averagePurchasePrice', $allProductsResponse, 1);
        Assert::assertJsonPathEquals('31.00', '*.lastPurchasePrice', $allProductsResponse, 1);
        Assert::assertJsonPathEquals('35', '*.inventory', $allProductsResponse, 1);

        Assert::assertJsonPathEquals('34.67', '*.averagePurchasePrice', $allProductsResponse, 1);
        Assert::assertJsonPathEquals('34.67', '*.lastPurchasePrice', $allProductsResponse, 1);
        Assert::assertJsonPathEquals('6', '*.inventory', $allProductsResponse, 1);

        $category1ProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/' . $subCategoryId1 . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.product.id', $category1ProductsResponse);
        Assert::assertJsonPathEquals($productId1, '0.product.id', $category1ProductsResponse);
        Assert::assertJsonPathEquals('28.60', '0.averagePurchasePrice', $category1ProductsResponse);
        Assert::assertJsonPathEquals('31.00', '0.lastPurchasePrice', $category1ProductsResponse);
        Assert::assertJsonPathEquals('35', '0.inventory', $category1ProductsResponse);

        $category2ProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/' . $subCategoryId2 . '/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.product.id', $category2ProductsResponse);
        Assert::assertJsonPathEquals($productId2, '0.product.id', $category2ProductsResponse);
        Assert::assertJsonPathEquals('34.67', '0.averagePurchasePrice', $category2ProductsResponse);
        Assert::assertJsonPathEquals('34.67', '0.lastPurchasePrice', $category2ProductsResponse);
        Assert::assertJsonPathEquals('6', '0.inventory', $category2ProductsResponse);
    }

    public function testAmountAndInventoryFieldsPresentAndHaveSameValues()
    {
        $storeId = $this->factory->store()->getStoreId('1');
        $departmentManager = $this->factory->store()->getDepartmentManager($storeId);
        $productId = $this->createProduct('1');
        $invoiceStoreId1 = $this->createInvoice(array('number' => 'invoice1'), $storeId);
        $this->createInvoiceProduct($invoiceStoreId1, $productId, 3, 19.99, $storeId);
        $invoiceStoreId2 = $this->createInvoice(array('number' => 'invoice2'), $storeId);
        $this->createInvoiceProduct($invoiceStoreId2, $productId, 4, 12.99, $storeId);

        $accessToken = $this->factory->oauth()->auth($departmentManager);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $productId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals(7, 'amount', $getResponse);
        Assert::assertJsonPathEquals(7, 'inventory', $getResponse);
    }

    public function testAdvancedSearchStoreProductsAction()
    {
        $storeId = $this->factory->store()->getStoreId();
        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);

        $this->createProduct(array('name' => 'Велосипед 3345', 'sku' => '111111111'));
        $this->createProduct(array('name' => 'Самокат', 'sku' => '2222222'));
        $this->createProduct(array('name' => 'Ролики детские', 'sku' => '33454453'));
        $this->createProduct(array('name' => 'Растишка курьёз', 'sku' => 'детское_питание_54453'));
        $this->createProduct(array('name' => 'Велосипед 8646', 'sku' => '111118646'));

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=Велосипед'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 3345', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 8646', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=11111'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 3345', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 8646', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=детс'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Ролики детские', '*.product.name', $response);
        Assert::assertJsonPathEquals('Растишка курьёз', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=3345'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 3345', '*.product.name', $response);
        Assert::assertJsonPathEquals('Ролики детские', '*.product.name', $response);
    }

    public function testAdvancedSearchStoreProductsActionOnlyOneProp()
    {
        $storeId = $this->factory->store()->getStoreId();
        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);

        $this->createProduct(array('name' => 'Велосипед 3345', 'sku' => '111111111'));
        $this->createProduct(array('name' => 'Самокат', 'sku' => '2222222'));
        $this->createProduct(array('name' => 'Ролики детские', 'sku' => '33454453'));
        $this->createProduct(array('name' => 'Растишка курьёз', 'sku' => 'детское_питание_54453'));
        $this->createProduct(array('name' => 'Велосипед 8646', 'sku' => '111118646'));

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&query=Велосипед'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 3345', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 8646', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=sku&query=11111'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 3345', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 8646', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&query=3345'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.inventory', $response);
        Assert::assertJsonPathCount(1, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 3345', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=sku&query=детс'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.inventory', $response);
        Assert::assertJsonPathCount(1, '*.product.name', $response);
        Assert::assertJsonPathEquals('Растишка курьёз', '*.product.name', $response);
    }

    public function testAdvancedSearchStoreProductsActionMilti()
    {
        $storeId = $this->factory->store()->getStoreId();
        $accessToken = $this->factory->oauth()->authAsDepartmentManager($storeId);

        $this->createProduct(array('name' => 'Пиво светлое Балтика', 'sku' => '111111111'));
        $this->createProduct(array('name' => 'Пиво ERDINGER светлое', 'sku' => '2222222'));
        $this->createProduct(array('name' => 'Светлые косы', 'sku' => '33454453'));

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=Пиво светл'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Пиво светлое Балтика', '*.product.name', $response);
        Assert::assertJsonPathEquals('Пиво ERDINGER светлое', '*.product.name', $response);
    }
}
