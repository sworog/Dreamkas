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
     * @dataProvider retailPriceValidateDataProvider
     */
    public function testPutActionRetailPriceValidate($expectedCode, array $data, array $assertions = array())
    {
        $productData = array(
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
                    'retailPrice' => 30,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Значение должно быть больше или равно 31',
                    'children.retailMarkup.errors' => null,
                )
            ),
            'invalid price mare than max' => array(
                400,
                array(
                    'retailPrice' => 41,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPrice.errors.0' => 'Значение должно быть меньше или равно 40',
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
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше или равна 1.71',
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
                    'children.retailMarkup.errors.0' => 'Наценка должна быть меньше или равна 31.23',
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
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше или равна 1.71',
                    'children.retailPrice.errors' => null,
                )
            ),
            'invalid markup mare than max just a little' => array(
                400,
                array(
                    'retailMarkup' => 31.24,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть меньше или равна 31.23',
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
                    'children.retailMarkup.errors.0' => 'Значение должно быть числом',
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
                    'children.retailMarkup.errors.0' => 'Значение не должно содержать больше 2 цифр после запятой',
                    'children.retailPrice.errors' => null,
                )
            ),
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
}
