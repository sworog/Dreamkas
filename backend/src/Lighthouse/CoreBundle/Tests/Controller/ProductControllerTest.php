<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Exception;
use MongoDuplicateKeyException;

class ProductControllerTest extends WebTestCase
{
    /**
     * @dataProvider productProvider
     */
    public function testPostProductAction(array $postData)
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals('10001', 'sku', $postResponse);
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
        $productData['retailMarkupMin'] = '';
        $productData['retailMarkupMax'] = '';
        $productData['retailPriceMin'] = '';
        $productData['retailPriceMax'] = '';
        $productData['retailPricePreference'] = 'retailMarkup';

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $productData
        );

        $this->assertResponseCode(201);

        Assert::assertNotJsonHasPath('retailMarkupMin', $responseJson);
        Assert::assertNotJsonHasPath('retailMarkupMax', $responseJson);
        Assert::assertNotJsonHasPath('retailPriceMin', $responseJson);
        Assert::assertNotJsonHasPath('retailPriceMax', $responseJson);

        $responseJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' .$responseJson['id']
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $responseJson);
        Assert::assertNotJsonHasPath('retailMarkupMax', $responseJson);
        Assert::assertNotJsonHasPath('retailPriceMin', $responseJson);
        Assert::assertNotJsonHasPath('retailPriceMax', $responseJson);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testPostProductInvalidData($expectedCode, array $data, array $assertions = array())
    {
        $postData = $data + $this->getProductData();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    public function testPostProductActionOnlyOneErrorMessageOnNotBlank()
    {
        $invalidData = $this->getProductData();
        $invalidData['units'] = '';

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $invalidData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathCount(1, 'children.units.errors.*', $response);
    }

    public function testPostProductActionEmptyPost()
    {
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products'
        );
        $this->assertResponseCode(400);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductAction(array $postData)
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);

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

        $this->assertResponseCode(200);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($putData['barcode'], 'barcode', $response);
        Assert::assertJsonPathEquals($putData['vat'], 'vat', $response);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionNotFound(array $putData)
    {
        $id = '1234534312';

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $putData['subCategory'] = $this->createSubCategory();

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        $this->assertResponseCode(404);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionInvalidData(array $postData)
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);

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

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Заполните это поле', 'children.name.errors.0', $response);
    }

    /**
     * @dataProvider productProvider
     */
    public function testPutProductActionChangeId(array $postData)
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);

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

        $this->assertResponseCode(400);
        Assert::assertJsonPathContains('Эта форма не должна содержать дополнительных полей', 'errors.0', $response);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $newId
        );

        $this->assertResponseCode(404);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );

        $this->assertResponseCode(200);
    }

    /**
     * @dataProvider productProvider
     */
    public function testGetProductsAction(array $postData)
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        for ($i = 0; $i < 5; $i++) {
            $postData['name'] = 'Кефир' . $i;
            $this->clientJsonRequest(
                $accessToken,
                'POST',
                '/api/1/products',
                $postData
            );
            $this->assertResponseCode(201);
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $response);
        Assert::assertJsonPathEquals('10001', '0.sku', $response);
        Assert::assertJsonPathEquals('10002', '1.sku', $response);
        Assert::assertJsonPathEquals('10003', '2.sku', $response);
        Assert::assertJsonPathEquals('10004', '3.sku', $response);
        Assert::assertJsonPathEquals('10005', '4.sku', $response);
    }

    /**
     * @dataProvider productProvider
     */
    public function testGetProduct(array $postData)
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('Кефир "Веселый Молочник" 1% 950гр', 'name', $getResponse);
        Assert::assertNotJsonHasPath('retailPricePreferences', $getResponse);
    }

    public function testGetProductNotFound()
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/1111'
        );
        $this->assertResponseCode(404);
    }

    public function testGetSubCategoryProducts()
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

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

        $this->assertResponseCode(200);

        foreach ($productsSubCategory1 as $productId) {
            Assert::assertJsonPathEquals($productId, '*.id', $jsonResponse);
        }


        $jsonResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId2 . '/products'
        );

        $this->assertResponseCode(200);

        foreach ($productsSubCategory2 as $productId) {
            Assert::assertJsonPathEquals($productId, '*.id', $jsonResponse);
        }
    }

    public function testGetSubCategoryProductsHaveNoSubcategoryField()
    {
        $subCategoryId = $this->createSubCategory();
        $this->createProductsByNames(array('1', '2', '3', '4', '5'));

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId . '/products'
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $response);
        Assert::assertJsonPathCount(0, '*.subCategory', $response);
    }

    /**
     * @dataProvider searchProductsProvider
     * @param string $property
     * @param string $query
     * @param array $expectedSkus
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testSearchProductsAction($property, $query, array $expectedSkus)
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->createProduct(array('name' => 'Кефир3', 'purchasePrice' => ''));
        $this->createProduct(array('name' => 'кефир веселый молочник'));
        $this->createProduct(array('name' => 'Батон /Россия/ .12', 'vendor' => 'Россия'));
        $this->createProduct(array('name' => 'Кефир грустный дойщик'));
        $this->createProduct(array('name' => 'кефир5', 'barcode' => '00127463212'));

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $property . '/search?' . $query
        );

        Assert::assertJsonPathCount(count($expectedSkus), '*.sku', $response);
        foreach ($expectedSkus as $expectedSku) {
            Assert::assertJsonPathEquals($expectedSku, '*.sku', $response, 1);
        }
    }

    /**
     * @return array
     */
    public function searchProductsProvider()
    {
        return array(
            'by sku' => array(
                'sku',
                'query=10002',
                array('10002')
            ),
            'by name' => array(
                'name',
                'query=Кефир3',
                array('10001')
            ),
            'by barcode' => array(
                'barcode',
                'query=00127463212',
                array('10005')
            ),
            'by name lowercase' => array(
                'name',
                'query=кефир3',
                array('10001')
            ),
            'by name two words' => array(
                'name',
                'query=молочник кефир',
                array('10002')
            ),
            'by name not exact match' => array(
                'name',
                'query=кефир',
                array('10001', '10002', '10004', '10005')
            ),
            'by name regex char /' => array(
                'name',
                'query=/россия/',
                array('10003')
            ),
            'by name regex char . does not match any char' => array(
                'name',
                'query=.ефир',
                array()
            ),
            'by name with .' => array(
                'name',
                'query=.12',
                array('10003')
            ),
            'field not intended for search but present in product' => array(
                'vendor',
                'query=Россия',
                array()
            ),
            'invalid field' => array(
                'invalid',
                'query=Россия',
                array()
            ),
            'not empty purchase price' => array(
                'name',
                'query=кефир&purchasePriceNotEmpty=1',
                array('10002', '10004', '10005')
            ),
            '1 letters' => array(
                'name',
                'query=к',
                array()
            ),
            '2 letters' => array(
                'name',
                'query=ке',
                array()
            ),
            '3 letters' => array(
                'name',
                'query=дой',
                array('10004')
            ),
            'lot of spaces' => array(
                'name',
                'query=кеф               мол',
                array('10002')
            ),
        );
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
            'empty purchasePrice' => array(
                201,
                array(
                    'purchasePrice' => '',

                ),
                array('purchasePrice' => null),
            ),
            'not valid price very float' => array(
                400,
                array('purchasePrice' => '10,898'),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('purchasePrice' => '10.898'),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
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
                    'Значение должно быть числом',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('purchasePrice' => 0),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
                ),
            ),
            'not valid price negative' => array(
                400,
                array('purchasePrice' => -10),
                array(
                    'children.purchasePrice.errors.0'
                    =>
                    'Цена не должна быть меньше или равна нулю'
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
            'sku should not be present' => array(
                400,
                array('sku' => 'qwe223sdw'),
                array('errors.0' => 'Эта форма не должна содержать дополнительных полей: "sku"'),
            ),
            /***********************************************************************************************
             * 'subCategory'
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
                    'Заполните это поле'
                ),
            ),
        );
    }

    /**
     * @dataProvider validRetailPriceProvider
     */
    public function testPostProductActionSetRetailsPriceValid(array $postData, array $assertions = array())
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData['subCategory'] = $this->createSubCategory();

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);

        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @dataProvider invalidRetailPriceProvider
     */
    public function testPostProductActionSetRetailsPriceInvalid(array $postData, array $assertions = array())
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postData += $this->getProductData(true);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(400);

        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @dataProvider validRetailPriceProvider
     */
    public function testPutProductActionSetRetailPriceValid(array $putData, array $assertions = array())
    {
        $postData = $this->getProductData();

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        $id = $postResponse['id'];

        $putData['subCategory'] = $postData['subCategory'];

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        $this->assertResponseCode(200);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/' . $id
        );

        $this->assertResponseCode(200);

        $this->performJsonAssertions($getResponse, $assertions);
    }

    /**
     * @dataProvider invalidRetailPriceProvider
     */
    public function testPutProductActionSetRetailPriceInvalid(array $putData, array $assertions = array())
    {
        $postData = $this->getProductData();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        $id = $postResponse['id'];

        $putData += $postData;
        $putData['subCategory'] = $postData['subCategory'];

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $id,
            $putData
        );

        $this->assertResponseCode(400);

        $this->performJsonAssertions($putResponse, $assertions);
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
                    'retailPriceMin' => 32.03,
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => 33.53,
                    'retailMarkupMax' => 12,
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPriceMin' => '32.03',
                    'retailMarkupMin' => '5.09',
                    'retailPriceMax' => '33.53',
                    'retailMarkupMax' => '10.01',
                    'retailPricePreference' => 'retailPrice',
                )
            ),
            'prefer markup, price invalid' => array(
                array(
                    'retailPriceMin' => 32.03,
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => 34.00,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '32.03',
                    'retailMarkupMin' => '5.09',
                    'retailPriceMax' => '33.53',
                    'retailMarkupMax' => '10.01',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer price, markup valid' => array(
                array(
                    'retailPriceMin' => 32.03,
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => 33.53,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPriceMin' => '32.03',
                    'retailMarkupMin' => '5.09',
                    'retailPriceMax' => '33.53',
                    'retailMarkupMax' => '10.01',
                    'retailPricePreference' => 'retailPrice',
                )
            ),
            'prefer markup, price valid' => array(
                array(
                    'retailPriceMin' => 32.03,
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => 33.53,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '32.03',
                    'retailMarkupMin' => '5.09',
                    'retailPriceMax' => '33.53',
                    'retailMarkupMax' => '10.01',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer markup, price not entered' => array(
                array(
                    'retailPriceMin' => '',
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => '',
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '32.03',
                    'retailMarkupMin' => '5.09',
                    'retailPriceMax' => '33.53',
                    'retailMarkupMax' => '10.01',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer price, markup not entered' => array(
                array(
                    'retailPriceMin' => 32.03,
                    'retailMarkupMin' => '',
                    'retailPriceMax' => 33.53,
                    'retailMarkupMax' => '',
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPriceMin' => '32.03',
                    'retailMarkupMin' => '5.09',
                    'retailPriceMax' => '33.53',
                    'retailMarkupMax' => '10.01',
                    'retailPricePreference' => 'retailPrice',
                )
            ),
            'prefer price, no price and markup entered' => array(
                array(
                    'retailPricePreference' => 'retailPrice',
                ) + $productData,
                array(
                    'retailPricePreference' => 'retailPrice',
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                ),
            ),
            'prefer markup, no price and markup entered' => array(
                array(
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPricePreference' => 'retailMarkup',
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                )
            ),
            'prefer markup, price valid, valid markup with comma: 10,11' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPriceMin' => 33.56,
                    'retailMarkupMin' => '10,11',
                    'retailPriceMax' => 33.70,
                    'retailMarkupMax' => '10,56',
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '33.56',
                    'retailMarkupMin' => '10.11',
                    'retailPriceMax' => '33.70',
                    'retailMarkupMax' => '10.56',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer price, valid price with comma, valid markup with comma' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPriceMin' => "33,56",
                    'retailMarkupMin' => "10,11",
                    'retailPriceMax' => "33,70",
                    'retailMarkupMax' => "10,56",
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '33.56',
                    'retailMarkupMin' => '10.11',
                    'retailPriceMax' => '33.70',
                    'retailMarkupMax' => '10.56',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer markup, price valid, valid markup: 0' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPriceMin' => 30.48,
                    'retailMarkupMin' => 0,
                    'retailPriceMax' => 30.48,
                    'retailMarkupMax' => 0,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '30.48',
                    'retailMarkupMin' => '0',
                    'retailPriceMax' => '30.48',
                    'retailMarkupMax' => '0',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer markup, valid markup: 0, price: 0' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPriceMin' => 0,
                    'retailMarkupMin' => 0,
                    'retailPriceMax' => 0,
                    'retailMarkupMax' => 10,
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '30.48',
                    'retailMarkupMin' => '0',
                    'retailPriceMax' => '33.53',
                    'retailMarkupMax' => '10',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer empty, valid markup: 10, empty price' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPriceMin' => '',
                    'retailMarkupMin' => 10,
                    'retailPriceMax' => '',
                    'retailMarkupMax' => 11,
                ) + $productData,
                array(
                    'retailPriceMin' => '33.53',
                    'retailMarkupMin' => '10',
                    'retailPriceMax' => '33.83',
                    'retailMarkupMax' => '11',
                    'retailPricePreference' => 'retailMarkup',
                )
            ),
            'prefer retailMarkup, valid markup: 9.3, price: 11.54' => array(
                array(
                    'purchasePrice' => '10.56',
                    'retailPriceMin' => 11.10,
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => '11.54',
                    'retailMarkupMax' => '9.3',
                    'retailPricePreference' => 'retailMarkup',
                ) + $productData,
                array(
                    'retailPriceMin' => '11.1',
                    'retailMarkupMin' => '5.09',
                    'retailPriceMax' => '11.54',
                    'retailMarkupMax' => '9.3',
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
        return array(
            // Валидация цены закупки
            'prefer price, markup valid, invalid price: 3 digits after coma' => array(
                array(
                    'retailPriceMin' => 30.48,
                    'retailMarkupMin' => 0,
                    'retailPriceMax' => 33.537,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMax.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                )
            ),
            'prefer price, markup valid, invalid price lower than purchase price' => array(
                array(
                    'purchasePrice' => 30.48,
                    'retailPriceMin' => 28.05,
                    'retailMarkupMin' => 1.01,
                    'retailPriceMax' => 33.53,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Цена продажи должна быть больше или равна цене закупки',
                    'children.retailMarkupMin.errors' => null
                ),
            ),
            'prefer price, markup valid, invalid price: -10.12' => array(
                array(
                    'retailPriceMin' => -10.12,
                    'retailMarkupMin' => 10.01,
                    'retailPriceMax' => 33.53,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Цена не должна быть меньше или равна нулю',
                ),
            ),
            'prefer price, markup valid, min price more than max price' => array(
                array(
                    'retailPriceMin' => 33.53,
                    'retailMarkupMin' => 10.01,
                    'retailPriceMax' => 30.48,
                    'retailMarkupMax' => 0,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Минимальная цена продажи не должна быть больше максимальной',
                    'children.retailPriceMax.errors' => null,
                ),
            ),
            'prefer markup, price valid, invalid markup: -105' => array(
                array(
                    'retailPriceMin' => 40,
                    'retailMarkupMin' => -105,
                    'retailPriceMax' => 50,
                    'retailMarkupMax' => 20,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Наценка должна быть равна или больше 0%',
                    'children.retailMarkupMin.errors.1' => null,
                    'children.retailMarkupMax.errors' => null,
                ),
            ),
            'prefer markup, price valid, invalid markup: -0.1' => array(
                array(
                    'retailPriceMin' => 30.50,
                    'retailMarkupMin' => -0.1,
                    'retailPriceMax' => 40,
                    'retailMarkupMax' => 10,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Наценка должна быть равна или больше 0%',
                    'children.retailMarkupMin.errors.1' => null,
                    'children.retailMarkupMax.errors' => null,
                ),
            ),
            'prefer markup, price valid, invalid markup: aaaa' => array(
                array(
                    'retailPriceMin' => 40,
                    'retailMarkupMin' => 'aaaa',
                    'retailPriceMax' => 50,
                    'retailMarkupMax' => 20,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Значение должно быть числом',
                    'children.retailMarkupMin.errors.1' => null,
                    'children.retailMarkupMax.errors' => null,
                ),
            ),
            'prefer markup, invalid markup: 3 digits after coma' => array(
                array(
                    'retailPriceMin' => 33.53,
                    'retailMarkupMin' => 10.001,
                    'retailPriceMax' => 33.53,
                    'retailMarkupMax' => 20,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Значение не должно содержать больше 2 цифр после запятой',
                ),
            ),
            'prefer price, empty markup, invalid price' => array(
                array(
                    'purchasePrice' => '30,48',
                    'retailPriceMin' => 'not valid',
                    'retailMarkupMin' => '',
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Значение должно быть числом',
                    'children.retailPriceMin.errors.1' => null,
                ),
            ),
            'prefer price, empty markup, invalid price, empty purchasePrice' => array(
                array(
                    'purchasePrice' => '',
                    'retailPriceMin' => 'not valid',
                    'retailMarkupMin' => '',
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Значение должно быть числом',
                    'children.purchasePrice.errors.0' => null,
                ),
            ),
            'prefer price, empty markup, prices with space' => array(
                array(
                    'purchasePrice' => '1112',
                    'retailMarkupMax' =>  '20',
                    'retailMarkupMin' => '15',
                    'retailPriceMax' =>  '1 334,40',
                    'retailPriceMin' => '1 278,80',
                    'retailPricePreference' => 'retailPrice'
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Значение должно быть числом',
                    'children.retailPriceMax.errors.0' => 'Значение должно быть числом',
                ),
            ),
            'prefer markup, empty price, invalid markup' => array(
                array(
                    'purchasePrice' => '34.33',
                    'retailPriceMin' => '',
                    'retailMarkupMin' => 'not valid',
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Значение должно быть числом',
                    'children.retailPriceMin.errors' => null,
                    'children.purchasePrice.errors' => null
                ),
            ),
            // Min > Max
            'prefer markup, min markup more than max markup' => array(
                array(
                    'retailPriceMax' => 32.03,
                    'retailMarkupMax' => 5.09,
                    'retailPriceMin' => 34.00,
                    'retailMarkupMin' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Минимальная наценка не должна быть больше максимальной',
                    'children.retailPriceMin.errors' => null,
                    'children.retailMarkupMax.errors' => null,
                    'children.retailPriceMax.errors' => null,
                    'children.purchasePrice.errors' => null
                ),
            ),
            'prefer price, min price more than max price' => array(
                array(
                    'retailPriceMax' => 32.03,
                    'retailMarkupMax' => 5.09,
                    'retailPriceMin' => 34.00,
                    'retailMarkupMin' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Минимальная цена продажи не должна быть больше максимальной',
                    'children.retailMarkupMin.errors' => null,
                    'children.retailMarkupMax.errors' => null,
                    'children.retailPriceMax.errors' => null,
                    'children.purchasePrice.errors' => null
                ),
            ),
            // Missing min or max field
            'prefer price, min price valid, max price empty' => array(
                array(
                    'retailPriceMin' => 32.03,
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => '',
                    'retailMarkupMax' => '',
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMax.errors.0' => 'Заполните это поле',
                ),
            ),
            'prefer price, max price valid, min price empty' => array(
                array(
                    'retailPriceMin' => '',
                    'retailMarkupMin' => '',
                    'retailPriceMax' => 34.00,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Заполните это поле',
                ),
            ),
            'prefer markup, min markup valid, max markup empty' => array(
                array(
                    'retailPriceMin' => 32.03,
                    'retailMarkupMin' => 5.09,
                    'retailPriceMax' => '',
                    'retailMarkupMax' => '',
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMax.errors.0' => 'Заполните это поле',
                    'children.retailMarkupMax.errors.1' => null,
                    'children.retailMarkupMin.errors' => null,
                ),
            ),
            'prefer markup, max markup valid, min markup empty' => array(
                array(
                    'retailPriceMin' => '',
                    'retailMarkupMin' => '',
                    'retailPriceMax' => 34.00,
                    'retailMarkupMax' => 10.01,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Заполните это поле',
                    'children.retailMarkupMin.errors.1' => null,
                    'children.retailMarkupMax.errors' => null,
                ),
            ),
            // No Purchase Price
            'no purchasePrice, retailPrice given' => array(
                array(
                    'purchasePrice' => '',
                    'retailPriceMin' => 28.45,
                    'retailPriceMax' => 34.00,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.retailPriceMin.errors.0' => 'Нельзя ввести цену продажи при отсутствии закупочной цены',
                    'children.retailPriceMin.errors.1' => null,
                    'children.retailPriceMax.errors.0' => 'Нельзя ввести цену продажи при отсутствии закупочной цены',
                    'children.retailPriceMax.errors.1' => null,
                ),
            ),
            'no purchasePrice, retailMarkup given' => array(
                array(
                    'purchasePrice' => '',
                    'retailMarkupMin' => 28.45,
                    'retailMarkupMax' => 34.00,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.retailMarkupMin.errors.0' => 'Нельзя ввести наценку при отсутствии закупочной цены',
                    'children.retailMarkupMin.errors.1' => null,
                    'children.retailMarkupMax.errors.0' => 'Нельзя ввести наценку при отсутствии закупочной цены',
                    'children.retailMarkupMax.errors.1' => null,
                ),
            ),
            'purchasePrice invalid, retailPrice given' => array(
                array(
                    'purchasePrice' => '20.123',
                    'retailPriceMin' => 28.45,
                    'retailPriceMax' => 34.00,
                    'retailPricePreference' => 'retailPrice',
                ),
                array(
                    'children.purchasePrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                    'children.purchasePrice.errors.1' => null,
                    'children.retailPriceMin.errors.0' => null,
                    'children.retailPriceMax.errors.0' => null,
                ),
            ),
            'purchasePrice invalid, retailMarkup given' => array(
                array(
                    'purchasePrice' => '20.123',
                    'retailMarkupMin' => 28.45,
                    'retailMarkupMax' => 34.00,
                    'retailPricePreference' => 'retailMarkup',
                ),
                array(
                    'children.purchasePrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                    'children.purchasePrice.errors.1' => null,
                    'children.retailMarkupMin.errors.0' => null,
                    'children.retailMarkupMax.errors.0' => null,
                ),
            )
        );
    }

    /**
     * @param $expectedCode
     * @param array $putData
     * @param array $assertions
     *
     * @dataProvider putProductActionSetRoundingProvider
     */
    public function testPutProductActionSetRounding($expectedCode, array $putData, array $assertions)
    {
        $postData = $this->getProductData();

        $productId = $this->createProduct($postData);

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $putData += $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $productId,
            $putData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions);

        if (200 == $expectedCode) {
            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/products/' . $productId
            );

            $this->assertEquals($putResponse, $getResponse, 'PUT and GET responses should be equal');
        }
    }

    /**
     * @return array
     */
    public function putProductActionSetRoundingProvider()
    {
        return array(
            'nearest1' => array(
                200,
                array(
                    'rounding' => 'nearest1',
                ),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
            'nearest10' => array(
                200,
                array(
                    'rounding' => 'nearest10',
                ),
                array(
                    'rounding.name' => 'nearest10',
                )
            ),
            'nearest50' => array(
                200,
                array(
                    'rounding' => 'nearest50',
                ),
                array(
                    'rounding.name' => 'nearest50',
                )
            ),
            'nearest100' => array(
                200,
                array(
                    'rounding' => 'nearest100',
                ),
                array(
                    'rounding.name' => 'nearest100',
                )
            ),
            'nearest99' => array(
                200,
                array(
                    'rounding' => 'nearest99',
                ),
                array(
                    'rounding.name' => 'nearest99',
                )
            ),
            'invalid rounding name' => array(
                400,
                array(
                    'rounding' => 'invalid10',
                ),
                array(
                    'children.rounding.errors.0' => 'Значение недопустимо.',
                )
            ),
            'invalid 0 rounding' => array(
                400,
                array(
                    'rounding' => 0,
                ),
                array(
                    'children.rounding.errors.0' => 'Значение недопустимо.',
                )
            ),
            'null rounding becomes default nearest1' => array(
                200,
                array(
                    'rounding' => null,
                ),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
            'empty rounding becomes default nearest1' => array(
                200,
                array(
                    'rounding' => '',
                ),
                array(
                    'rounding.name' => 'nearest1',
                )
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
                    'type' => 'unit',
                    'barcode' => '4607025392408',
                    'purchasePrice' => 30.48,
                    'vat' => 10,
                    'vendor' => 'Вимм-Билль-Данн',
                    'vendorCountry' => 'Россия',
                    'info' => 'Классный кефирчик, употребляю давно, всем рекомендую для поднятия тонуса',
                )
            )
        );
    }

    /**
     * @param bool $withSubCategory
     * @return mixed
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
    public function testAccessProduct($url, $method, $role, $responseCode, array $requestData = array())
    {
        $subCategoryId = $this->factory->catalog()->getSubCategory('Пиво')->id;
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
        $accessToken = $this->factory->oauth()->authAsRole($role);
        $requestData += $this->getProductData();

        $this->clientJsonRequest(
            $accessToken,
            $method,
            $url,
            $requestData
        );

        $this->assertResponseCode($responseCode);
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
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                200,
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
            /*************************************
             * POST /api/1/products
             */
            array(
                '/api/1/products',
                'POST',
                User::ROLE_COMMERCIAL_MANAGER,
                201,
            ),
            array(
                '/api/1/products',
                'POST',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/products',
                'POST',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/products',
                'POST',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
            /*************************************
             * PUT /api/1/products/__PRODUCT_ID__
             */
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
            /*************************************
             * GET /api/1/subcategories/__SUBCATEGORY_ID__/products
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                200,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
        );
    }

    /**
     * @group unique
     */
    public function testNotUniqueSkuInParallel()
    {
        $productData = $this->getProductData();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/products', 'POST', $productData);
        $jsonRequest->setAccessToken($accessToken);

        $responses = $this->client->parallelJsonRequest($jsonRequest, 3);
        $statusCodes = array();
        $jsonResponses = array();
        foreach ($responses as $response) {
            $statusCodes[] = $response->getStatusCode();
            $jsonResponses[] = $this->client->decodeJsonResponse($response);
        }
        $exporter = new Exporter();
        $responseBody = $exporter->export($jsonResponses);
        $this->assertCount(3, array_keys($statusCodes, 201), $responseBody);
        Assert::assertJsonPathEquals('Кефир "Веселый Молочник" 1% 950гр', '*.name', $jsonResponses, 3);
        Assert::assertJsonPathEquals('10001', '*.sku', $jsonResponses, 1);
        Assert::assertJsonPathEquals('10002', '*.sku', $jsonResponses, 1);
        Assert::assertJsonPathEquals('10003', '*.sku', $jsonResponses, 1);
    }

    protected function doPostActionFlushFailedException(\Exception $exception)
    {
        $productData = $this->getProductData();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $product = new Product();

        $documentManagerMock = $this->getMock(
            'Doctrine\\ODM\\MongoDB\\DocumentManager',
            array(),
            array(),
            '',
            false
        );
        $documentManagerMock
            ->expects($this->once())
            ->method('persist');

        $documentManagerMock
            ->expects($this->once())
            ->method('flush')
            ->with($this->isEmpty())
            ->will($this->throwException($exception));

        $productRepoMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\Product\\ProductRepository',
            array(),
            array(),
            '',
            false
        );

        $productRepoMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($product));
        $productRepoMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($productRepoMock) {
                $container->set('lighthouse.core.document.repository.product', $productRepoMock);
            }
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $productData
        );

        return $response;
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedException()
    {
        $exception = new Exception('Unknown exception');
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(500);
        Assert::assertJsonPathEquals('Unknown exception', 'message', $response);
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedMongoDuplicateKeyException()
    {
        $exception = new MongoDuplicateKeyException();
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Validation Failed', 'message', $response);
        Assert::assertJsonPathEquals('Такой артикул уже есть', 'errors.0', $response);
    }

    public function testPostActionToSubCategoryWithMarkup()
    {
        $category = $this->factory()->catalog()->getCategory();
        $subCategory = $this
            ->factory()
            ->catalog()
            ->createSubCategory($category->id, 'Подкатегория с наценкой', null, 10, 20);

        $productData = $this->getProductData(false);
        unset($productData['purchasePrice']);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $productData + array(
                'subCategory' => $subCategory->id
            )
        );

        $this->assertResponseCode(201);
        foreach ($productData as $prop => $value) {
            Assert::assertJsonPathEquals($value, $prop, $response);
        }
    }
}
