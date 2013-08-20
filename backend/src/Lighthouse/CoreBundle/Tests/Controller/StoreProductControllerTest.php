<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

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
        $this->clearMongoDb();
        $this->storeManager = $this->createUser('Василий Петрович Краузе', 'password', User::ROLE_STORE_MANAGER);

        $this->productId = $this->createProduct();
        $this->storeId = $this->createStore();

        $this->linkStoreManagers($this->storeId, $this->storeManager->id);
    }

    public function testGetActionNoStoreProductCreated()
    {
        $accessToken = $this->auth($this->storeManager, 'password');

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
        $accessToken = $this->auth($this->storeManager, 'password');

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
        $accessToken = $this->auth($this->storeManager, 'password');

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

        $accessToken = $this->auth($this->storeManager, 'password');

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
                    'children.retailPrice.errors.0' => 'Цена не должна быть меньше или равна нулю.',
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
                    'children.retailPrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой.',
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
            'invalid markup less than 0 when no min max product markup provided' => array(
                400,
                array(
                    'retailMarkup' => -2,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше или равна 0%',
                    'children.retailMarkup.errors.1' => null,
                    'children.retailPrice.errors' => null,
                ),
                array(
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'valid markup equals 0 when no min max product markup provided' => array(
                200,
                array(
                    'retailMarkup' => 0,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'retailMarkup' => 0,
                    'retailPrice' => '30.48',
                    'children' => null,
                ),
                array(
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'invalid price less than purchasePrice when no min max product markup provided' => array(
                400,
                array(
                    'retailPrice' => 30.45,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Цена продажи должна быть больше или равна цене закупки.',
                    'children.retailPrice.errors.1' => null,
                    'children.retailMarkup.errors' => null,
                ),
                array(
                    'purchasePrice' => 30.48,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'valid price equals purchasePrice when no min max product markup provided' => array(
                200,
                array(
                    'retailPrice' => 30.48,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'retailPrice' => '30.48',
                    'retailMarkup' => '0',
                    'children' => null,
                ),
                array(
                    'purchasePrice' => 30.48,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                    'retailPricePreference' => 'retailMarkup',
                )
            )
        );
    }

    public function testStoreManagerAccessHasNoStore()
    {
        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

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
        $accessToken = $this->auth($this->storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $this->storeId . '/products/' . $this->productId
        );

        $this->assertResponseCode(200);
    }

    public function testGetStoreSubCategoryProductsStoreManagerHasStore()
    {
        $accessToken = $this->auth($this->storeManager, 'password');

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

        $accessToken = $this->auth($this->storeManager, 'password');

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
            ),
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

        $accessToken = $this->auth($this->storeManager, 'password');

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
}
