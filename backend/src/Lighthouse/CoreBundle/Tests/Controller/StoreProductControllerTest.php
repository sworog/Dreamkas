<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Product\Store\StoreProductMetricsCalculator;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Document\User\User;

class StoreProductControllerTest extends WebTestCase
{
    public function testGetActionNoStoreProductCreated()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($product->id, 'product.id', $getResponse);
        Assert::assertJsonPathEquals($store->id, 'store.id', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
    }

    public function testGetActionProductDoesNotExist()
    {
        $store = $this->factory()->store()->getStore();
        $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/aaaa"
        );

        $this->assertResponseCode(404);
        Assert::assertJsonPathContains('No route found', 'message', $getResponse, false);
    }

    public function testGetActionProductExistsStoreNotExists()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/aaa/products/{$product->id}"
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
        $store = $this->factory()->store()->getStore();
        $subCategory = $this->factory()->catalog()->getSubCategory();

        $productData += array(
            'name' => 'Водка селедка',
            'purchasePrice' => 30.48,
            'retailPriceMin' => 31,
            'retailPriceMax' => 40,
            'retailPricePreference' => 'retailPrice',
            'subCategory' => $subCategory->id
        );

        $product = $this->factory()->catalog()->createProductByForm($productData);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $putData = $data;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/products/{$product->id}",
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
                    'errors.children.retailPrice.errors.0' => 'Цена должна быть больше или равна 31.00',
                    'errors.children.retailPrice.errors.1' => null,
                    'errors.children.retailMarkup.errors' => null,
                )
            ),
            'invalid price less than min and purchasePrice' => array(
                400,
                array(
                    'retailPrice' => 30,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'errors.children.retailPrice.errors.0' => 'Цена должна быть больше или равна 31.00',
                    'errors.children.retailPrice.errors.1' => null,
                    'errors.children.retailMarkup.errors' => null,
                ),
            ),
            'invalid price more than max' => array(
                400,
                array(
                    'retailPrice' => 41,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'errors.children.retailPrice.errors.0' => 'Цена должна быть меньше или равна 40.00',
                    'errors.children.retailPrice.errors.1' => null,
                    'errors.children.retailMarkup.errors' => null,
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
                    'errors.children.retailMarkup.errors.0' => 'Наценка должна быть больше или равна 1.71%',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
                )
            ),
            'invalid markup mare than max' => array(
                400,
                array(
                    'retailMarkup' => 32,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'errors.children.retailMarkup.errors.0' => 'Наценка должна быть меньше или равна 31.23%',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
                )
            ),
            'invalid markup less than min just a little' => array(
                400,
                array(
                    'retailMarkup' => 1.7,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'errors.children.retailMarkup.errors.0' => 'Наценка должна быть больше или равна 1.71%',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
                )
            ),
            'invalid markup more than max just a little' => array(
                400,
                array(
                    'retailMarkup' => 31.24,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'errors.children.retailMarkup.errors.0' => 'Наценка должна быть меньше или равна 31.23%',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
                )
            ),
            'invalid price string' => array(
                400,
                array(
                    'retailPrice' => 'aaaa',
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'errors.children.retailPrice.errors.0' => 'Значение должно быть числом',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailMarkup.errors' => null,
                )
            ),
            'invalid price 3 digits after comma' => array(
                400,
                array(
                    'retailPrice' => 35.555,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'errors.children.retailPrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailMarkup.errors' => null,
                )
            ),
            'invalid markup string' => array(
                400,
                array(
                    'retailMarkup' => 'aaaa',
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'errors.children.retailMarkup.errors.0' => 'Наценка должна быть числом',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
                )
            ),
            'invalid markup 3 digits after comma' => array(
                400,
                array(
                    'retailMarkup' => 2.234,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'errors.children.retailMarkup.errors.0'
                    =>
                    'Наценка не должна содержать больше 2 цифр после запятой',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
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
                    'errors.children.retailPrice.errors.0'
                    =>
                    'Нельзя установить цену продажи, если не установлен диапозон цены продажи',
                    'errors.children.retailPrice.errors.1' => null,
                    'errors.children.retailMarkup.errors'  => null,
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
                    'errors.children.retailMarkup.errors.0'
                    =>
                    'Нельзя установить наценку, если не установлен диапозон наценки',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
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
                    'errors.children.retailMarkup.errors.0'
                    =>
                    'Нельзя установить наценку, если не установлен диапозон наценки',
                    'errors.children.retailMarkup.errors.1' => null,
                    'errors.children.retailPrice.errors' => null,
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
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_STORE_MANAGER);

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    public function testStoreManagerAccessHasStore()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}"
        );

        $this->assertResponseCode(200);
    }

    public function testDepartmentManagerAccessHasNoStore()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_DEPARTMENT_MANAGER);

        $this->client->setCatchException();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}"
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    public function testDepartmentManagerAccessHasStore()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}"
        );

        $this->assertResponseCode(200);
    }

    public function testGetStoreSubCategoryProductsStoreManagerHasStore()
    {
        $store = $this->factory()->store()->getStore();
        $subCategory = $this->factory()->catalog()->getSubCategory('Вино сухое');
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'), $subCategory);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$subCategory->id}/products"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.product', $getResponse);
        foreach ($products as $product) {
            Assert::assertJsonPathEquals($product->id, '*.product.id', $getResponse, 1);
        }
    }

    public function testGetStoreSubCategoryProductsHasNoCategoryAndStore()
    {
        $store = $this->factory()->store()->getStore();
        $subCategory = $this->factory()->catalog()->getSubCategory('Вино сухое');
        $this->factory()->catalog()->getProductByNames(array('1', '2', '3'), $subCategory);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$subCategory->id}/products"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.product', $getResponse);
        Assert::assertJsonPathCount(0, '*.product.subCategory.category', $getResponse);
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
        $store = $this->factory()->store()->getStore();
        $subCategory = $this->factory()->catalog()->getSubCategory();

        $productData += array(
            'name' => 'Водка селедка',
            'purchasePrice' => 30.48,
            'retailPriceMin' => 31,
            'retailPriceMax' => 40,
            'retailPricePreference' => 'retailPrice',
            'rounding' => $productRounding,
            'subCategory' => $subCategory->id
        );

        $product = $this->factory()->catalog()->createProductByForm($productData);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $putData = $data + array('retailPricePreference' => 'retailPrice');

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/stores/{$store->id}/products/{$product->id}",
            $putData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions);

        if (200 == $expectedCode) {
            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                "/api/1/stores/{$store->id}/products/{$product->id}"
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
                    'errors.children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
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
                    'errors.children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
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
                    'errors.children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
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
                    'errors.children.retailPrice.errors.0' => 'Цена после округления должна быть больше 0',
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
        $store = $this->factory()->store()->getStore();

        $subCategory = $this->factory()->catalog()->getSubCategory();

        $productData += array(
            'name' => 'Водка селедка',
            'purchasePrice' => 30.48,
            'retailPriceMin' => 31,
            'retailPriceMax' => 40,
            'retailPricePreference' => 'retailPrice',
            'subCategory' => $subCategory->id
        );

        $product = $this->factory()->catalog()->createProductByForm($productData);

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('id', $getResponse);
        Assert::assertJsonPathEquals($product->id, 'product.id', $getResponse);
        Assert::assertJsonPathEquals($store->id, 'store.id', $getResponse);

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
        $store1 = $this->factory()->store()->getStore('1');
        $store2 = $this->factory()->store()->getStore('2');

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $departmentAccessToken1 = $this->factory()->oauth()->authAsDepartmentManager($store1->id);
        $departmentAccessToken2 = $this->factory()->oauth()->authAsDepartmentManager($store2->id);

        $response = $this->clientJsonRequest(
            $departmentAccessToken1,
            'GET',
            "/api/1/stores/{$store1->id}/products"
        );

        $this->assertResponseCode(200);
        foreach ($products as $product) {
            Assert::assertJsonPathEquals($product->id, '*.product.id', $response, 1);
        }

        Assert::assertJsonPathEquals(0, '*.inventory', $response);

        $response = $this->clientJsonRequest(
            $departmentAccessToken2,
            'GET',
            "/api/1/stores/{$store2->id}/products"
        );

        $this->assertResponseCode(200);
        foreach ($products as $product) {
            Assert::assertJsonPathEquals($product->id, '*.product.id', $response, 1);
        }

        Assert::assertJsonPathEquals(0, '*.inventory', $response, 3);


        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store1->id)
                ->createInvoiceProduct($products['1']->id, 1, 10.99)
                ->createInvoiceProduct($products['2']->id, 2, 11.99)
            ->flush();

        $response = $this->clientJsonRequest(
            $departmentAccessToken1,
            'GET',
            "/api/1/stores/{$store1->id}/products"
        );

        $this->assertResponseCode(200);
        foreach ($products as $product) {
            Assert::assertJsonPathEquals($product->id, '*.product.id', $response, 1);
        }

        Assert::assertJsonPathEquals(1, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(2, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(0, '*.inventory', $response, 1);

        Assert::assertJsonPathEquals(10.99, '*.lastPurchasePrice', $response, 1);
        Assert::assertJsonPathEquals(11.99, '*.lastPurchasePrice', $response, 1);

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store1->id)
                ->createInvoiceProduct($products['1']->id, 3, 9.99)
                ->createInvoiceProduct($products['2']->id, 4, 12.99)
            ->flush();

        $response = $this->clientJsonRequest(
            $departmentAccessToken1,
            'GET',
            "/api/1/stores/{$store1->id}/products"
        );

        $this->assertResponseCode(200);
        foreach ($products as $product) {
            Assert::assertJsonPathEquals($product->id, '*.product.id', $response, 1);
        }

        Assert::assertJsonPathEquals(4, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(6, '*.inventory', $response, 1);
        Assert::assertJsonPathEquals(0, '*.inventory', $response, 1);

        Assert::assertJsonPathEquals(9.99, '*.lastPurchasePrice', $response, 1);
        Assert::assertJsonPathEquals(12.99, '*.lastPurchasePrice', $response, 1);
    }

    public function testPurchasePriceTotalsAndAmountAreReturnedUsingGetSubcategoryProductsMethod()
    {
        $this->authenticateProject();

        $catalog = $this->factory()->catalog()->createCatalog(
            array(
                '1' => array(
                    '1.1' => array(
                        '1.1.1' => array(),
                        '1.1.2' => array()
                    )
                )
            )
        );


        $product1 = $this->factory()->catalog()->getProduct('1', $catalog['1.1.1']);
        $product2 = $this->factory()->catalog()->getProduct('2', $catalog['1.1.2']);

        $this->factory()->catalog()->getProduct('other');

        $store = $this->factory()->store()->getStore('666');

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-31 days'))), $store->id)
                ->createInvoiceProduct($product1->id, 10, 23.33)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-3 days'))), $store->id)
                ->createInvoiceProduct($product1->id, 10, 26)
                ->createInvoiceProduct($product2->id, 6, 34.67)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-2 days'))), $store->id)
                ->createInvoiceProduct($product1->id, 5, 29)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => date('c', strtotime('-1 days'))), $store->id)
                ->createInvoiceProduct($product1->id, 10, 31)
            ->flush();

        /* @var $averagePriceService StoreProductMetricsCalculator */
        $averagePriceService = $this->getContainer()->get('lighthouse.core.service.product.metrics_calculator');
        $averagePriceService->recalculateAveragePrice();

        $accessToken = $this->factory()->oauth()->authAsStoreManager($store->id);

        $allProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products"
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
            "/api/1/stores/{$store->id}/subcategories/{$catalog['1.1.1']->id}/products"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.product.id', $category1ProductsResponse);
        Assert::assertJsonPathEquals($product1->id, '0.product.id', $category1ProductsResponse);
        Assert::assertJsonPathEquals('28.60', '0.averagePurchasePrice', $category1ProductsResponse);
        Assert::assertJsonPathEquals('31.00', '0.lastPurchasePrice', $category1ProductsResponse);
        Assert::assertJsonPathEquals('35', '0.inventory', $category1ProductsResponse);

        $category2ProductsResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/subcategories/{$catalog['1.1.2']->id}/products"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.product.id', $category2ProductsResponse);
        Assert::assertJsonPathEquals($product2->id, '0.product.id', $category2ProductsResponse);
        Assert::assertJsonPathEquals('34.67', '0.averagePurchasePrice', $category2ProductsResponse);
        Assert::assertJsonPathEquals('34.67', '0.lastPurchasePrice', $category2ProductsResponse);
        Assert::assertJsonPathEquals('6', '0.inventory', $category2ProductsResponse);
    }

    public function testAmountAndInventoryFieldsPresentAndHaveSameValues()
    {
        $store = $this->factory()->store()->getStore();
        $product = $this->factory()->catalog()->getProduct('1');

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($product->id, 3, 19.99)
            ->flush();

        $this->factory()
            ->invoice()
                ->createInvoice(array(), $store->id)
                ->createInvoiceProduct($product->id, 4, 12.99)
            ->flush();

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products/{$product->id}"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals(7, 'amount', $getResponse);
        Assert::assertJsonPathEquals(7, 'inventory', $getResponse);
    }

    public function testAdvancedSearchStoreProductsAction()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $this->createProduct(array('name' => 'Велосипед 2010004')); // 10002
        $this->createProduct(array('name' => 'Самокат'));  // 10003
        $this->createProduct(array('name' => 'Ролики детские')); // 10004
        $this->createProduct(array('name' => 'Растишка курьёз')); // 10005
        $this->createProduct(array('name' => 'Велосипед 10006'));// 10006

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=Велосипед'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 2010004', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 10006', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=1000'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(6, '*.inventory', $response);
        Assert::assertJsonPathCount(6, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 2010004', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 10006', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=детс'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.inventory', $response);
        Assert::assertJsonPathCount(1, '*.product.name', $response);
        Assert::assertJsonPathEquals('Ролики детские', '*.product.name', $response);


        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=name&properties[]=sku&query=10004'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед 2010004', '*.product.name', $response);
        Assert::assertJsonPathEquals('Ролики детские', '*.product.name', $response);
    }

    public function testAdvancedSearchStoreProductsActionOnlyOneProp()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $this->createProduct(array('name' => 'Велосипед 3345'));
        $this->createProduct(array('name' => 'Самокат'));
        $this->createProduct(array('name' => 'Ролики детские'));
        $this->createProduct(array('name' => 'Растишка курьёз'));
        $this->createProduct(array('name' => 'Велосипед 8646'));

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
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=sku&query=1000'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(6, '*.inventory', $response);
        Assert::assertJsonPathCount(6, '*.product.name', $response);
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
            '/api/1/stores/' . $storeId . '/search/products' . '?properties[]=sku&query=10005'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.inventory', $response);
        Assert::assertJsonPathCount(1, '*.product.name', $response);
        Assert::assertJsonPathEquals('Растишка курьёз', '*.product.name', $response);
    }

    public function testAdvancedSearchStoreProductsActionMulti()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($storeId);

        $this->factory()->catalog()->getProduct('Пиво светлое Балтика');
        $this->factory()->catalog()->getProduct('Пиво ERDINGER светлое');
        $this->factory()->catalog()->getProduct('Светлые косы');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$storeId}/search/products",
            array(),
            array('properties' => array('name', 'sku'), 'query' => 'Пиво светл')
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Пиво светлое Балтика', '*.product.name', $response);
        Assert::assertJsonPathEquals('Пиво ERDINGER светлое', '*.product.name', $response);
    }

    public function testGetStoreProductsWithSubCategoryFilter()
    {
        $store = $this->factory()->store()->getStore();

        $subCategory1 = $this->factory()->catalog()->getSubCategory('1');
        $subCategory2 = $this->factory()->catalog()->getSubCategory('2');

        $this->factory()->catalog()->getProduct('11', $subCategory1);
        $this->factory()->catalog()->getProduct('12', $subCategory1);
        $this->factory()->catalog()->getProduct('21', $subCategory2);
        $this->factory()->catalog()->getProduct('22', $subCategory2);
        $this->factory()->catalog()->getProduct('23', $subCategory2);

        $accessToken = $this->factory()->oauth()->authAsStoreManager();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products",
            array(),
            array('subCategory' => $subCategory1->id)
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, '*.inventory', $getResponse);
        Assert::assertJsonPathCount(2, '*.product.name', $getResponse);
        Assert::assertJsonPathContains('11', '*.product.name', $getResponse);
        Assert::assertJsonPathContains('12', '*.product.name', $getResponse);

        $accessToken = $this->factory()->oauth()->authAsStoreManager();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products",
            array(),
            array('subCategory' => $subCategory2->id)
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(3, '*.inventory', $getResponse);
        Assert::assertJsonPathCount(3, '*.product.name', $getResponse);
        Assert::assertJsonPathContains('21', '*.product.name', $getResponse);
        Assert::assertJsonPathContains('22', '*.product.name', $getResponse);
        Assert::assertJsonPathContains('23', '*.product.name', $getResponse);
    }

    /**
     * @dataProvider getStoreProductsWithProductFilterProvider
     * @param string $query
     * @param int $expectedInventoryCount
     * @param int $expectedProductsCount
     * @param array $assertions
     */
    public function testGetStoreProductsWithProductFilter(
        $query,
        $expectedInventoryCount,
        $expectedProductsCount,
        array $assertions
    ) {
        $store = $this->factory()->store()->getStore();
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);

        $this->factory()->catalog()->getProduct('Велосипед 2010004'); // 10002
        $this->factory()->catalog()->getProduct('Самокат');  // 10003
        $this->factory()->catalog()->getProduct('Ролики детские'); // 10004
        $this->factory()->catalog()->getProduct('Растишка курьёз'); // 10005
        $this->factory()->catalog()->getProduct('Велосипед 10006');// 10006

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products",
            array(),
            array('properties' => array('name', 'sku'), 'query' => $query)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount($expectedInventoryCount, '*.inventory', $response);
        Assert::assertJsonPathCount($expectedProductsCount, '*.product.name', $response);
        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function getStoreProductsWithProductFilterProvider()
    {
        return array(
            'Велосипед' => array(
                'Велосипед',
                2,
                2,
                array(
                    '0.product.name' => 'Велосипед 2010004',
                    '1.product.name' => 'Велосипед 10006',
                )
            ),
            '1000' => array(
                '1000',
                6,
                6,
                array(
                    '1.product.name' => 'Велосипед 2010004',
                    '5.product.name' => 'Велосипед 10006',
                )
            ),
            'детс' => array(
                'детс',
                1,
                1,
                array(
                    '0.product.name' => 'Ролики детские',
                )
            ),
            '10004' => array(
                '10004',
                2,
                2,
                array(
                    '0.product.name' => 'Велосипед 2010004',
                    '1.product.name' => 'Ролики детские',
                )
            )
        );
    }

    public function testGetStoreProductsWithProductAndSubCategoryFilter()
    {
        $store = $this->factory()->store()->getStore();
        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $subCategory1 = $this->factory()->catalog()->getSubCategory('Женские лясики');
        $subCategory2 = $this->factory()->catalog()->getSubCategory('Мужские лясипеды');

        $this->factory()->catalog()->getProduct('Велосипед Женский', $subCategory1); // 10002
        $this->factory()->catalog()->getProduct('Велосипед Унисекс', $subCategory1);  // 10003
        $this->factory()->catalog()->getProduct('Велосипед Мужекс', $subCategory2); // 10004
        $this->factory()->catalog()->getProduct('Велосипед Девекс', $subCategory1); // 10005
        $this->factory()->catalog()->getProduct('Велосипед Мужской', $subCategory2);// 10006

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products",
            array(),
            array('properties' => array('name', 'sku'), 'query' => 'екс')
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.inventory', $response);
        Assert::assertJsonPathCount(3, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед Унисекс', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед Девекс', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед Мужекс', '*.product.name', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/stores/{$store->id}/products",
            array(),
            array('properties' => array('name', 'sku'), 'query' => 'екс', 'subCategory' => $subCategory1->id)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.inventory', $response);
        Assert::assertJsonPathCount(2, '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед Унисекс', '*.product.name', $response);
        Assert::assertJsonPathEquals('Велосипед Девекс', '*.product.name', $response);
    }
}
