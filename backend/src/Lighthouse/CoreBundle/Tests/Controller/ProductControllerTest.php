<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
    }

    /**
     * @dataProvider productProvider
     */
    public function testPostProductAction(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonPathEquals(30.48, 'purchasePrice', $postResponse);
        Assert::assertNotJsonHasPath('lastPurchasePrice', $postResponse);
        Assert::assertJsonHasPath('subCategory', $postResponse);
    }

    /**
     * Тест на проблему сохраниения пустой наценки и цены продажи
     */
    public function testPostRetailPriceEmpty()
    {
        $productData = $this->getProductData();
        $productData['retailMarkup'] = '';
        $productData['retailPrice'] = '';
        $productData['retailPricePreference'] = 'retailMarkup';

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $productData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertNotJsonHasPath('retailMarkup', $responseJson);
        Assert::assertNotJsonHasPath('retailPrice', $responseJson);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' .$responseJson['id']
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertNotJsonHasPath('retailMarkup', $responseJson);
        Assert::assertNotJsonHasPath('retailPrice', $responseJson);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostProductInvalidData($expectedCode, array $data, array $assertions = array())
    {
        $postData = $data + $this->getProductData();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    public function testPostProductActionOnlyOneErrorMessageOnNotBlank()
    {
        $invalidData = $this->getProductData();
        $invalidData['purchasePrice'] = '';
        $invalidData['units'] = '';

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $invalidData
        );


        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathCount(1, 'children.purchasePrice.errors.*', $response);
        Assert::assertJsonPathCount(1, 'children.units.errors.*', $response);
    }

    public function testPostProductActionEmptyPost()
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products'
        );
        Assert::assertResponseCode(400, $this->client);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPostProductActionUniqueSku(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains('уже есть', 'children.sku.errors.0', $response);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductAction(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonPathEquals($postData['barcode'], 'barcode', $response);
        Assert::assertJsonPathEquals($postData['vat'], 'vat', $response);

        Assert::assertJsonHasPath('id', $response);
        $id = $response['id'];
        $this->assertNotEmpty($id);

        $putData = $postData;
        $putData['barcode'] = '65346456456';
        $putData['vat'] = 18;

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        Assert::assertResponseCode(200, $this->client);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathEquals($putData['barcode'], 'barcode', $response);
        Assert::assertJsonPathEquals($putData['vat'], 'vat', $response);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionNotFound(array $putData)
    {
        $id = '1234534312';

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $putData['subCategory'] = $this->createSubCategory();

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        Assert::assertResponseCode(404, $this->client);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionInvalidData(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonPathEquals($postData['barcode'], 'barcode', $response);
        Assert::assertJsonPathEquals($postData['vat'], 'vat', $response);

        Assert::assertJsonHasPath('id', $response);
        $id = $response['id'];
        $this->assertNotEmpty($id);

        $putData = $postData;
        unset($putData['name']);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains('Заполните это поле', 'children.name.errors.0', $response);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionChangeId(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $response);
        $id = $response['id'];
        $this->assertNotEmpty($id);

        $newId = 123;
        $putData = $postData;
        $putData['id'] = $newId;

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        Assert::assertResponseCode(400, $this->client);
        Assert::assertJsonPathContains('Эта форма не должна содержать дополнительных полей', 'errors.0', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $newId
        );

        Assert::assertResponseCode(404, $this->client);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );

        Assert::assertResponseCode(200, $this->client);
    }

    public function testCorsHeader()
    {
        $postArray = array(
            'name' => 'Кефир',
        );

        $headers = array(
            'HTTP_Origin' => 'www.a.com',
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postArray,
            array(),
            $headers
        );

        /* @var $response Response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->has('Access-Control-Allow-Origin'));

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postArray
        );
        /* @var $response Response */
        $response = $this->client->getResponse();
        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
    }

    /**
     * @dataProvider productProvider
     */
    public function testGetProductsAction(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        for ($i = 0; $i < 5; $i++) {
            $postData['name'] = 'Кефир' . $i;
            $postData['sku'] = 'sku' . $i;
            $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/products',
                $postData
            );
            Assert::assertResponseCode(201, $this->client);
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products'
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathCount(5, '*.id', $response);
    }

    /**
     * @dataProvider productProvider
     */
    public function testGetProduct(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );
        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathEquals('Кефир "Веселый Молочник" 1% 950гр', 'name', $getResponse);
        Assert::assertNotJsonHasPath('retailPricePreferences', $getResponse);
    }

    public function testGetProductNotFound()
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/1111'
        );
        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetSubCategoryProducts()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $subCategoryId1 = $this->createSubCategory(null, 'Пиво');
        $subCategoryId2 = $this->createSubCategory(null, 'Водка');

        $productsSubCategory1 = array();
        $productsSubCategory2 = array();

        for ($i = 0; $i < 5; $i++) {
            $productsSubCategory1[] = $this->createProduct("пиво ". $i, $subCategoryId1);
            $productsSubCategory2[] = $this->createProduct("водка ". $i, $subCategoryId2);
        }

        $jsonResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId1 . '/products'
        );

        Assert::assertResponseCode(200, $this->client);

        foreach ($productsSubCategory1 as $productId) {
            Assert::assertJsonPathEquals($productId, '*.id', $jsonResponse);
        }


        $jsonResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId2 . '/products'
        );

        Assert::assertResponseCode(200, $this->client);

        foreach ($productsSubCategory2 as $productId) {
            Assert::assertJsonPathEquals($productId, '*.id', $jsonResponse);
        }
    }

    /**
     * @dataProvider productProvider
     */
    public function testSearchProductsAction(array $postData)
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        for ($i = 0; $i < 5; $i++) {
            $postData['name'] = 'Кефир' . $i;
            $postData['sku'] = 'sku' . $i;
            $response = $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/products',
                $postData
            );
            Assert::assertResponseCode(201, $this->client);
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/name/search' . '?query=кефир3'
        );

        Assert::assertJsonPathCount(1, '*.name', $response);
        Assert::assertJsonPathEquals('Кефир3', '*.name', $response);
    }

    public function testSearchProductsActionEmptyRequest()
    {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/invalid/search'
        );

        Assert::assertResponseCode(200, $this->client);

        $this->assertInternalType('array', $response);
        $this->assertCount(0, $response);
    }

    public function validateProvider()
    {
        return array(
            /***********************************************************************************************
             * 'name'
             ***********************************************************************************************/
            'valid name' => array(
                201,
                array('name' => 'test'),
            ),
            'valid name 300 chars' => array(
                201,
                array('name' => str_repeat('z', 300)),
            ),
            'empty name' => array(
                400,
                array('name' => ''),
                array(
                    'children.name.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid name too long' => array(
                400,
                array('name' => str_repeat("z", 305)),
                array(
                    'children.name.errors.0'
                    =>
                    'Не более 300 символов',
                ),
            ),
            /***********************************************************************************************
             * 'purchasePrice'
             ***********************************************************************************************/
            'valid price dot' => array(
                201,
                array('purchasePrice' => 10.89),
            ),
            'valid price dot 79.99' => array(
                201,
                array('purchasePrice' => 79.99),
            ),
            'valid price coma' => array(
                201,
                array('purchasePrice' => '10,89'),
            ),
            'empty price' => array(
                400,
                array('purchasePrice' => ''),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid price very float' => array(
                400,
                array('purchasePrice' => '10,898'),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой.'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('purchasePrice' => '10.898'),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой.'
                ),
            ),
            'valid price very float with dot' => array(
                201,
                array('purchasePrice' => '10.12')
            ),
            'not valid price not a number' => array(
                400,
                array('purchasePrice' => 'not a number'),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю.',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('purchasePrice' => 0),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю.'
                ),
            ),
            'not valid price negative' => array(
                400,
                array('purchasePrice' => -10),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю.'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('purchasePrice' => 2000000001),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('purchasePrice' => '100000000'),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            'valid price too big 10 000 000' => array(
                201,
                array('purchasePrice' => '10000000'),
            ),
            'not valid price too big 10 000 001' => array(
                400,
                array('purchasePrice' => '10000001'),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть больше 10000000'
                ),
            ),
            /***********************************************************************************************
             * 'vat'
             ***********************************************************************************************/
            'valid vat' => array(
                201,
                array('vat' => 18),
            ),
            'valid vat zero' => array(
                201,
                array('vat' => 0),
            ),
            'not valid vat not a number' => array(
                400,
                array('vat' => 'not a number'),
                array(
                    'children.vat.errors.0'
                    =>
                    'Значение должно быть числом.',
                ),
            ),
            'not valid vat negative' => array(
                400,
                array('vat' => -30),
                array(
                    'children.vat.errors.0'
                    =>
                    'Значение должно быть 0 или больше.',
                ),
            ),
            'not valid vat empty' => array(
                400,
                array('vat' => ''),
                array(
                    'children.vat.errors.0'
                    =>
                    'Выберите ставку НДС',
                ),
            ),
            /***********************************************************************************************
             * 'barcode'
             ***********************************************************************************************/
            'valid barcode' => array(
                201,
                array('barcode' => 'ijashglkalgh2378rt8237t4rjhdg '),
            ),
            'valid barcode empty' => array(
                201,
                array('barcode' => ''),
            ),
            'valid barcode 200 length' => array(
                201,
                array('barcode' => str_repeat('z', 200)),
            ),
            'not valid barcode too long' => array(
                400,
                array('barcode' => str_repeat("z", 201)),
                array(
                    'children.barcode.errors.0'
                    =>
                    'Не более 200 символов',
                ),
            ),
            /***********************************************************************************************
             * 'vendor'
             ***********************************************************************************************/
            'valid vendor' => array(
                201,
                array('vendor' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid vendor empty' => array(
                201,
                array('vendor' => ''),
            ),
            'valid vendor 300 length' => array(
                201,
                array('vendor' => str_repeat('z', 300)),
            ),
            'not valid vendor too long' => array(
                400,
                array('vendor' => str_repeat("z", 301)),
                array(
                    'children.vendor.errors.0'
                    =>
                    'Не более 300 символов',
                ),
            ),
            /***********************************************************************************************
             * 'vendorCountry'
             ***********************************************************************************************/
            'valid vendorCountry' => array(
                201,
                array('vendorCountry' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid vendorCountry empty' => array(
                201,
                array('vendorCountry' => ''),
            ),
            'valid vendorCountry 300 length' => array(
                201,
                array('vendorCountry' => str_repeat('z', 100)),
            ),
            'not valid vendorCountry too long' => array(
                400,
                array('vendorCountry' => str_repeat("z", 101)),
                array(
                    'children.vendorCountry.errors.0'
                    =>
                    'Не более 100 символов',
                ),
            ),
            /***********************************************************************************************
             * 'info'
             ***********************************************************************************************/
            'valid info' => array(
                201,
                array('info' => 'asdsadjhg2124jk 124 " 1!@3 - _ =_+[]<>$;&%#№'),
            ),
            'valid info empty' => array(
                201,
                array('info' => ''),
            ),
            'valid info 2000 length' => array(
                201,
                array('info' => str_repeat('z', 2000)),
            ),
            'not valid info too long' => array(
                400,
                array('info' => str_repeat("z", 2001)),
                array(
                    'children.info.errors.0'
                    =>
                    'Не более 2000 символов',
                ),
            ),
            /***********************************************************************************************
             * 'sku'
             ***********************************************************************************************/
            'valid sku' => array(
                201,
                array('sku' => 'qwe223sdw'),
            ),
            'valid sku 100 length' => array(
                201,
                array('sku' => str_repeat('z', 100)),
            ),
            'not valid sku empty' => array(
                400,
                array('sku' => ''),
                array(
                    'children.sku.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid sku too long' => array(
                400,
                array('sku' => str_repeat("z", 101)),
                array(
                    'children.sku.errors.0'
                    =>
                    'Не более 100 символов',
                ),
            ),
            /***********************************************************************************************
             * 'sku'
             ***********************************************************************************************/
            'not valid subCategory not exist' => array(
                400,
                array('subCategory' => 'not_exist_subCategory'),
                array(
                    'children.subCategory.errors.0'
                    =>
                    'Такой подкатегории не существует'
                ),
            ),
            'not valid subCategory empty' => array(
                400,
                array('subCategory' => ''),
                array(
                    'children.subCategory.errors.0'
                    =>
                    'Такой подкатегории не существует'
                ),
            ),
        );
    }

    /**
     * @dataProvider validRetailPriceProvider
     */
    public function testPostProductActionSetRetailsPriceValid(
        array $postData,
        array $assertions = array(),
        array $emptyAssertions = array()
    ) {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathEquals($expected, $path, $response);
        }

        foreach ($emptyAssertions as $path) {
            Assert::assertNotJsonHasPath($path, $response);
        }
    }

    /**
     * @dataProvider invalidRetailPriceProvider
     */
    public function testPostProductActionSetRetailsPriceInvalid(
        array $postData,
        array $assertions = array(),
        array $emptyAssertions = array()
    ) {
        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(400, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathEquals($expected, $path, $response);
        }

        foreach ($emptyAssertions as $path) {
            Assert::assertNotJsonHasPath($path, $response);
        }
    }

    /**
     * @dataProvider validRetailPriceProvider
     */
    public function testPutProductActionSetRetailPriceValid(
        array $putData,
        array $assertions = array(),
        array $emptyAssertions = array()
    ) {
        $postData = $this->getProductData();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        $id = $postResponse['id'];

        $putData['subCategory'] = $postData['subCategory'];
        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        Assert::assertResponseCode(200, $this->client);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );

        Assert::assertResponseCode(200, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathEquals($expected, $path, $getResponse);
        }

        foreach ($emptyAssertions as $path) {
            Assert::assertNotJsonHasPath($path, $getResponse);
        }
    }

    /**
     * @dataProvider invalidRetailPriceProvider
     */
    public function testPutProductActionSetRetailPriceInvalid(
        array $putData,
        array $assertions = array(),
        array $emptyAssertions = array()
    ) {
        $postData = $this->getProductData();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        $id = $postResponse['id'];

        $putData['subCategory'] = $postData['subCategory'];
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        Assert::assertResponseCode(400, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathEquals($expected, $path, $putResponse);
        }

        foreach ($emptyAssertions as $path) {
            Assert::assertNotJsonHasPath($path, $putResponse);
        }
    }

    /**
     * @return array
     */
    public function validRetailPriceProvider()
    {
        $productData = $this->getProductData(false);

        return array(
            'prefer price, markup invalid' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => 12,
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'retailPrice',
                )
            ),
            'prefer markup, price invalid' => array(
                array(
                    'retailPrice' => 34.00,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer price, markup valid' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'retailPrice',
                )
            ),
            'prefer markup, price valid' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer markup, price not entered' => array(
                array(
                    'retailPrice' => '',
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer price, markup not entered' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => '',
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10.01',
                    'retailPricePreference' => 'retailPrice',
                )
            ),
            'prefer price, no price and markup entered' => array(
                array(
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPricePreference' => 'retailPrice',
                ),
                /*
                array(
                    'retailPrice',
                    'retailMarkup',
                )
                */
            ),
            'prefer markup, no price and markup entered' => array(
                array(
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPricePreference' => 'retailMarkup',
                ),
                /*
                array(
                    'retailPrice',
                    'retailMarkup',
                )
                */
            ),
            'prefer markup, price valid, valid markup: -10' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPrice' => 27.42,
                    'retailMarkup' => -10,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '27.43',
                    'retailMarkup' => '-10',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer markup, price valid, valid markup with comma: -10,11' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPrice' => 27.40,
                    'retailMarkup' => "-10,11",
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '27.40',
                    'retailMarkup' => '-10.11',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer price, valid price with comma: 27,40' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPrice' => "27,40",
                    'retailMarkup' => "-10,11",
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '27.40',
                    'retailMarkup' => '-10.11',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer markup, price valid, valid markup: 0' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPrice' => 30.48,
                    'retailMarkup' => 0,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '30.48',
                    'retailMarkup' => '0',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer markup, valid markup: 0, price: 0' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPrice' => 0,
                    'retailMarkup' => 0,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '30.48',
                    'retailMarkup' => '0',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer empty, valid markup: 10, price: empty' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPrice' => '',
                    'retailMarkup' => 10,
                ) + $productData,
                array(
                    'retailPrice' => '33.53',
                    'retailMarkup' => '10',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer retailMarkup, valid markup: 9.3, price: 11.54' => array(
                array(
                    'purchasePrice' => '10.56',
                    'retailPrice' => '11.54',
                    'retailMarkup' => '9.3',
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPrice' => '11.54',
                    'retailMarkup' => '9.3',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
        );
    }


    /**
     * @return array
     */
    public function invalidRetailPriceProvider()
    {
        $postData = $this->getProductData(false);

        return array(
            // Валидация цены закупки
            'prefer price, markup valid, invalid price: 3 digits after coma' => array(
                array(
                    'retailPrice' => 33.537,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ) + $postData,
                array(
                    'children.retailPrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой.',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            'prefer price, markup valid, invalid price: 0' => array(
                array(
                    'retailPrice' => 0,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ) + $postData,
                array(
                    'children.retailPrice.errors.0' => 'Цена не должна быть меньше или равна нулю.',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            'prefer price, markup valid, invalid price: -10.12' => array(
                array(
                    'retailPrice' => -10.12,
                    'retailMarkup' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ) + $postData,
                array(
                    'children.retailPrice.errors.0' => 'Цена не должна быть меньше или равна нулю.',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            // Валидация наценки
            'prefer markup, price valid, invalid markup: -105' => array(
                array(
                    'retailPrice' => 10.12,
                    'retailMarkup' => -105,
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше -100%',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            'prefer markup, price valid, invalid markup: -100' => array(
                array(
                    'retailPrice' => 10.12,
                    'retailMarkup' => -100,
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше -100%',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            'prefer markup, price valid, invalid markup: aaaa' => array(
                array(
                    'retailPrice' => 10.12,
                    'retailMarkup' => 'aaaa',
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailMarkup.errors.0' => 'Значение должно быть числом',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            'prefer markup, valid markup -99.99, but price became 0' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPrice' => 0.00,
                    'retailMarkup' => -99.99,
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailPrice.errors.0' => 'Цена не должна быть меньше или равна нулю.',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            'prefer markup, invalid markup: 3 digits after coma' => array(
                array(
                    'retailPrice' => 33.53,
                    'retailMarkup' => 10.001,
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailMarkup.errors.0' => 'Значение не должно содержать больше 2 цифр после запятой',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference'
                ),
            ),
            'prefer price, empty markup, invalid price' => array(
                array(
                    'purchasePrice' => '30,48',
                    'retailPrice' => 'not valid',
                    'retailMarkup' => '',
                    'retailPricePreference' => 'retailPrice',
                ) + $postData,
                array(
                    'children.retailPrice.errors.0' => 'Цена не должна быть меньше или равна нулю.',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference', 'children.retailMarkup.errors'
                ),
            ),
            'prefer price, empty markup, invalid price, empty purchasePrice' => array(
                array(
                    'purchasePrice' => '',
                    'retailPrice' => 'not valid',
                    'retailMarkup' => '',
                    'retailPricePreference' => 'retailPrice',
                ) + $postData,
                array(
                    'children.retailPrice.errors.0' => 'Цена не должна быть меньше или равна нулю.',
                    'children.purchasePrice.errors.0' => 'Заполните это поле',
                ),
                array(
                    'retailPrice', 'retailMarkup', 'retailPricePreference', 'children.retailMarkup.errors'
                ),
            ),
            'prefer markup, empty price, invalid markup' => array(
                array(
                    'purchasePrice' => '34.33',
                    'retailPrice' => '',
                    'retailMarkup' => 'not valid',
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailMarkup.errors.0' => 'Значение должно быть числом',
                ),
                array(
                    'children.retailPrice.errors', 'children.purchasePrice.errors'
                ),
            ),
            'prefer markup, price 0,00, invalid markup -100' => array(
                array(
                    'purchasePrice' => '34.33',
                    'retailPrice' => '0,00',
                    'retailMarkup' => '-100',
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailMarkup.errors.0' => 'Наценка должна быть больше -100%',
                ),
                array(
                    'children.retailPrice.errors', 'children.purchasePrice.errors'
                ),
            ),
            'prefer markup, price 0,00, invalid markup -100.999' => array(
                array(
                    'purchasePrice' => '34.33',
                    'retailPrice' => '0,00',
                    'retailMarkup' => '-100.999',
                    'retailPricePreference' => 'retailMarkup',
                ) + $postData,
                array(
                    'children.retailMarkup.errors.*' => 'Наценка должна быть больше -100%',
                    'children.retailMarkup.errors.*' => 'Значение не должно содержать больше 2 цифр после запятой',
                ),
                array(
                    'children.retailPrice.errors', 'children.purchasePrice.errors'
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function productProvider()
    {
        return array(
            'milkman' => array(
                array(
                    'name' => 'Кефир "Веселый Молочник" 1% 950гр',
                    'units' => 'gr',
                    'barcode' => '4607025392408',
                    'purchasePrice' => 30.48,
                    'sku' => 'КЕФИР "ВЕСЕЛЫЙ МОЛОЧНИК" 1% КАРТОН УПК. 950ГР',
                    'vat' => 10,
                    'vendor' => 'Вимм-Билль-Данн',
                    'vendorCountry' => 'Россия',
                    'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
                )
            )
        );
    }

    /**
     * @return array
     */
    public function getProductData($withSubCategory = true)
    {
        $productData = $this->productProvider();
        if ($withSubCategory) {
            $subCategoryId = $this->createSubCategory();
            $productData['milkman'][0]['subCategory'] = $subCategoryId;
        }
        return $productData['milkman'][0];
    }



    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array|null $requestData
     *
     * @dataProvider accessProductProvider
     */
    public function testAccessProduct($url, $method, $role, $responseCode, $requestData = null)
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);
        $subCategoryId = $this->createSubCategory($categoryId);
        $productId = $this->createProduct('Старый мельник', $subCategoryId);

        $url = str_replace(
            array(
                '__PRODUCT_ID__',
                '__SUBCATEGORY_ID__',
            ),
            array(
                $productId,
                $subCategoryId,
            ),
            $url
        );
        $accessToken = $this->authAsRole($role);
        if (is_array($requestData)) {
            $requestData = $requestData + $this->getProductData();
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            $method,
            $url,
            $requestData
        );

        Assert::assertResponseCode($responseCode, $this->client);
    }

    public function accessProductProvider()
    {
        return array(
            /*************************************
             * GET /api/1/products/__PRODUCT_ID__
             */
            array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * POST /api/1/products
             */
            array(
                '/api/1/products',
                'POST',
                'ROLE_COMMERCIAL_MANAGER',
                '201',
                array(),
            ),
            array(
                '/api/1/products',
                'POST',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/products',
                'POST',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/products',
                'POST',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * PUT /api/1/products/__PRODUCT_ID__
             */
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
                array(),
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * GET /api/1/subcategories/__SUBCATEGORY_ID__/products
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),
        );
    }
}
