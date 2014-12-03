<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Type\AlcoholType;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType;
use Lighthouse\CoreBundle\Document\Product\Type\WeightType;
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
     * @param array $postData
     */
    public function testPostProductAction(array $postData)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory();

        $postData['subCategory'] = $subCategory->id;

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

    public function testPostProductWithSubCategory()
    {
        $postData = array(
            'subCategory' => array(
                'name' => 'Категория'
            )
        ) + $this->getProductData(false);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
        Assert::assertJsonHasPath('subCategory.id', $postResponse);
        Assert::assertJsonPathEquals('Категория', 'subCategory.name', $postResponse);
        Assert::assertJsonHasPath('subCategory.category.id', $postResponse);
    }

    public function testPostProductWithDuplicateSubCategoryName()
    {
        $postData = array(
                'subCategory' => array(
                    'name' => 'Категория'
                )
            ) + $this->getProductData(false);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('subCategory.id', $postResponse);
        Assert::assertJsonPathEquals('Категория', 'subCategory.name', $postResponse);
        Assert::assertJsonHasPath('subCategory.category.id', $postResponse);

        $secondResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathEquals(
            'Группа с таким названием уже существует',
            'errors.children.subCategory.children.name.errors.0',
            $secondResponse
        );
    }

    public function testPutProductWithSubCategoryName()
    {
        $postData = array(
            'subCategory' => array(
                'name' => 'Категория'
            )
        ) + $this->getProductData(false);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonHasPath('subCategory.id', $postResponse);
        Assert::assertJsonPathEquals('Категория', 'subCategory.name', $postResponse);
        Assert::assertJsonHasPath('subCategory.category.id', $postResponse);

        $subCategoryId = $postResponse['subCategory']['id'];
        $productId = $postResponse['id'];

        $putData = $postData;
        $putData['name'] = 'Товар';
        $putData['subCategory']['name'] = 'Группа';

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/products/' . $productId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, 'id', $putResponse);
        Assert::assertNotJsonPathEquals($subCategoryId, 'subCategory.id', $putResponse);
        Assert::assertJsonPathEquals('Группа', 'subCategory.name', $putResponse);
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

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     */
    public function testPostProductInvalidData($expectedCode, array $data, array $assertions = array())
    {
        $postData = $data + $this->getProductData();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
        $invalidData['name'] = '';

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $invalidData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathCount(1, 'errors.children.name.errors.*', $response);
    }

    public function testPostProductActionEmptyPost()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products'
        );
        $this->assertResponseCode(400);
    }

    /**
     * @dataProvider productProvider
     * @param array $postData
     */
    public function testPutProductAction(array $postData)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory();

        $postData['subCategory'] = $subCategory->id;

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
     * @param array $putData
     */
    public function testPutProductActionNotFound(array $putData)
    {
        $id = '1234534312';

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory();

        $postData['subCategory'] = $subCategory->id;

        $this->client->setCatchException();
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
     * @param array $postData
     */
    public function testPutProductActionInvalidData(array $postData)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory();
        $postData['subCategory'] = $subCategory->id;

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

        Assert::assertJsonPathContains('Заполните это поле', 'errors.children.name.errors.0', $response);
    }

    /**
     * @dataProvider productProvider
     * @param array $postData
     */
    public function testPutProductActionChangeId(array $postData)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory();

        $postData['subCategory'] = $subCategory->id;

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
        Assert::assertJsonPathContains(
            'Эта форма не должна содержать дополнительных полей',
            'errors.errors.0',
            $response
        );

        $this->client->setCatchException();
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

    public function testGetProductsAction()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->factory()->catalog()->getProductByNames(array('1', '2', '3', '4', '5'));

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
        Assert::assertJsonPathCount(5, '*.subCategory.id', $response);
    }

    /**
     * @dataProvider getProductsLimitProvider
     * @param int $limit
     * @param int $expectedCode
     * @param array $assertions
     * @param int $expectedCount
     */
    public function testGetProductsLimit($limit, $expectedCode, array $assertions = array(), $expectedCount = 0)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->factory()->catalog()->getProductByNames(array('1', '2', '3', '4', '5'));

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products',
            null,
            array('limit' => $limit)
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($response, $assertions);

        if ($expectedCode == 200) {
            Assert::assertJsonPathCount($expectedCount, '*.id', $response);
        }
    }

    /**
     * @return array
     */
    public function getProductsLimitProvider()
    {
        return array(
            /*
             * Positive
             */
            '3: should return 3 items' => array(
                3,
                200,
                array(
                    '0.sku' => '10001',
                    '1.sku' => '10002',
                    '2.sku' => '10003',
                ),
                3
            ),
            '1: should return only one item' => array(
                1,
                200,
                array(
                    '0.sku' => '10001',
                ),
                1
            ),
            '10' => array(
                10,
                200,
                array(
                    '0.sku' => '10001',
                    '1.sku' => '10002',
                    '2.sku' => '10003',
                    '3.sku' => '10004',
                    '4.sku' => '10005',
                ),
                5
            ),
            'null: limit should not be applied, return all available items' => array(
                null,
                200,
                array(
                    '0.sku' => '10001',
                    '1.sku' => '10002',
                    '2.sku' => '10003',
                    '3.sku' => '10004',
                    '4.sku' => '10005',
                ),
                5
            ),
            /*
             * Negative
             */
            '0: limit should be greater than 0' => array(
                0,
                400,
                array(
                    'errors.children.limit.errors.0' => 'Значение должно быть 1 или больше.'
                )
            ),
            '-10: limit should be greater than 0' => array(
                0,
                400,
                array(
                    'errors.children.limit.errors.0' => 'Значение должно быть 1 или больше.'
                )
            ),
            'aaa: limit should be integer, not string' => array(
                'aaa',
                400,
                array(
                    'errors.children.limit.errors.0' => 'Значение недопустимо.'
                )
            ),
            'array' => array(
                array(10),
                400,
                array(
                    'errors.children.limit.errors.0' => 'Значение недопустимо.'
                )
            )
        );
    }

    public function testGetProductsTotalCountHeader()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->factory()->catalog()->getProductByNames(array('a', 'b', 'c', 'd', 'e', 'f', 'g'));

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products',
            null,
            array('limit' => 5)
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $response);

        $headersBag = $this->client->getResponse()->headers;
        $this->assertTrue($headersBag->has('X-Total-Count'));
        $this->assertEquals('7', $headersBag->get('X-Total-Count'));
    }

    public function testGetProductsWithEmptyTypePropertiesReturnsArray()
    {
        $this->factory()->catalog()->getProductByNames(array('1', '2', '3', '4', '5'));

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products'
        );

        $this->assertResponseCode(200);

        $rawBody = $this->client->getResponse()->getContent();
        $this->assertStringStartsWith('[', $rawBody);
        $this->assertStringEndsWith(']', $rawBody);

        Assert::assertJsonPathCount(5, '*.id', $response);
    }

    /**
     * @dataProvider productProvider
     * @param array $postData
     */
    public function testGetProduct(array $postData)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory();

        $postData['subCategory'] = $subCategory->id;

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
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/1111'
        );
        $this->assertResponseCode(404);
    }

    public function testGetSubCategoryProducts()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory1 = $this->factory()->catalog()->getSubCategory('Пиво');
        $subCategory2 = $this->factory()->catalog()->getSubCategory('Водка');

        $productsSubCategory1 = array();
        $productsSubCategory2 = array();

        for ($i = 0; $i < 5; $i++) {
            $productsSubCategory1[] = $this->factory()->catalog()->getProduct("пиво {$i}", $subCategory1)->id;
            $productsSubCategory2[] = $this->factory()->catalog()->getProduct("водка {$i}", $subCategory2)->id;
        }

        $jsonResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory1->id}/products"
        );

        $this->assertResponseCode(200);

        foreach ($productsSubCategory1 as $productId) {
            Assert::assertJsonPathEquals($productId, '*.id', $jsonResponse);
        }

        Assert::assertJsonPathEquals($subCategory1->id, '*.subCategory.id', $jsonResponse, 5);
        Assert::assertNotJsonHasPath('*.subCategory.category.id', $jsonResponse);

        $jsonResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory2->id}/products"
        );

        $this->assertResponseCode(200);

        foreach ($productsSubCategory2 as $productId) {
            Assert::assertJsonPathEquals($productId, '*.id', $jsonResponse);
        }
    }

    public function testGetSubCategoryProductsHaveCategoryField()
    {
        $subCategory = $this->factory()->catalog()->getSubCategory();

        $this->factory()->catalog()->getProductByNames(array('1', '2', '3', '4', '5'), $subCategory);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}/products"
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $response);
        Assert::assertJsonPathCount(0, '*.subCategory.category', $response);
    }

    /**
     * @dataProvider searchProductsProvider
     * @param array $query
     * @param array $expectedSkus
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testSearchProductsAction(array $query, array $expectedSkus)
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир3', 'purchasePrice' => '')
        );
        $this->factory()->catalog()->createProduct(
            array('name' => 'кефир веселый молочник', 'purchasePrice' => '30.48')
        );
        $this->factory()->catalog()->createProduct(
            array('name' => 'Батон /Россия/ .12', 'vendor' => 'Россия', 'purchasePrice' => '30.48')
        );
        $this->factory()->catalog()->createProduct(
            array('name' => 'Кефир грустный дойщик', 'purchasePrice' => '30.48')
        );
        $this->factory()->catalog()->createProduct(
            array('name' => 'кефир5', 'barcode' => '00127463212', 'purchasePrice' => '30.48')
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/search',
            array(),
            $query
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
                array(
                    'properties' => array('sku'),
                    'query' => '10002'
                ),
                array('10002')
            ),
            'by name' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'Кефир3'
                ),
                array('10001')
            ),
            'by barcode' => array(
                array(
                    'properties' => array('barcode'),
                    'query' => '00127463212'
                ),
                array('10005')
            ),
            'by name lowercase' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'кефир3'
                ),
                array('10001')
            ),
            'by name two words' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'молочник кефир'
                ),
                array('10002')
            ),
            'by name not exact match' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'кефир'
                ),
                array('10001', '10002', '10004', '10005')
            ),
            'by name regex char /' => array(
                array(
                    'properties' => array('name'),
                    'query' => '/россия/'
                ),
                array('10003')
            ),
            'by name regex char . does not match any char' => array(
                array(
                    'properties' => array('name'),
                    'query' => '.ефир'
                ),
                array()
            ),
            'by name with .' => array(
                array(
                    'properties' => array('name'),
                    'query' => '.12'
                ),
                array('10003')
            ),
            'field not intended for search but present in product' => array(
                array(
                    'properties' => array('vendor'),
                    'query' => 'Россия'
                ),
                array()
            ),
            'invalid field' => array(
                array(
                    'properties' => array('invalid'),
                    'query' => 'Россия'
                ),
                array()
            ),
            'not empty purchase price' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'кефир',
                    'purchasePriceNotEmpty' => 1
                ),
                array('10002', '10004', '10005')
            ),
            '1 letters' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'к',
                ),
                array()
            ),
            '2 letters' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'ке',
                ),
                array()
            ),
            '3 letters' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'дой',
                ),
                array('10004')
            ),
            'lot of spaces' => array(
                array(
                    'properties' => array('name'),
                    'query' => 'кеф               мол',
                ),
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
                    'errors.children.name.errors.0'
                    =>
                    'Заполните это поле',
                ),
            ),
            'not valid name too long' => array(
                400,
                array('name' => str_repeat("z", 305)),
                array(
                    'errors.children.name.errors.0'
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
                    'errors.children.purchasePrice.errors.0'
                    =>
                    'Цена не должна содержать больше 2 цифр после запятой'
                ),
            ),
            'not valid price very float dot' => array(
                400,
                array('purchasePrice' => '10.898'),
                array(
                    'errors.children.purchasePrice.errors.0'
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
                    'errors.children.purchasePrice.errors.0' => 'Значение должно быть числом',
                ),
            ),
            'not valid price zero' => array(
                400,
                array('purchasePrice' => 0),
                array(
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна быть меньше или равна нулю'
                ),
            ),
            'not valid price negative' => array(
                400,
                array('purchasePrice' => -10),
                array(
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна быть меньше или равна нулю'
                )
            ),
            'not valid price too big 2 000 000 001' => array(
                400,
                array('purchasePrice' => 2000000001),
                array(
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна быть больше 10000000'
                ),
            ),
            'not valid price too big 100 000 000' => array(
                400,
                array('purchasePrice' => '100000000'),
                array(
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна быть больше 10000000'
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
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна быть больше 10000000'
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
                    'errors.children.vat.errors.0' => 'Значение должно быть числом.',
                ),
            ),
            'not valid vat negative' => array(
                400,
                array('vat' => -30),
                array(
                    'errors.children.vat.errors.0' => 'Значение должно быть 0 или больше.',
                ),
            ),
            'not valid vat empty' => array(
                400,
                array('vat' => ''),
                array(
                    'errors.children.vat.errors.0' => 'Выберите ставку НДС',
                ),
            ),
            /***********************************************************************************************
             * 'barcode'
             ***********************************************************************************************/
            'valid barcode' => array(
                201,
                array('barcode' => 'Problem resolution Save to dictionary '),
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
                    'errors.children.barcode.errors.0' => 'Не более 200 символов',
                ),
            ),
            /***********************************************************************************************
             * 'vendor'
             ***********************************************************************************************/
            'valid vendor' => array(
                201,
                array('vendor' => 'vendor 124 " 1!@3 - _ =_+[]<>$;&%#№'),
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
                    'errors.children.vendor.errors.0' => 'Не более 300 символов',
                ),
            ),
            /***********************************************************************************************
             * 'vendorCountry'
             ***********************************************************************************************/
            'valid vendorCountry' => array(
                201,
                array('vendorCountry' => 'vendor country 124 " 1!@3 - _ =_+[]<>$;&%#№'),
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
                    'errors.children.vendorCountry.errors.0' => 'Не более 100 символов',
                ),
            ),
            /***********************************************************************************************
             * 'info'
             ***********************************************************************************************/
            'valid info' => array(
                201,
                array('info' => 'info 124 " 1!@3 - _ =_+[]<>$;&%#№'),
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
                    'errors.children.info.errors.0' => 'Не более 2000 символов',
                ),
            ),
            /***********************************************************************************************
             * 'sku'
             ***********************************************************************************************/
            'sku should not be present' => array(
                400,
                array('sku' => 'qwe223sdw'),
                array('errors.errors.0' => 'Эта форма не должна содержать дополнительных полей: "sku"'),
            ),
            /***********************************************************************************************
             * 'subCategory'
             ***********************************************************************************************/
            'not valid subCategory not exist' => array(
                400,
                array('subCategory' => 'not_exist_subCategory'),
                array(
                    'errors.children.subCategory.errors.0' => 'Такой подкатегории не существует'
                ),
            ),
            'not valid subCategory empty' => array(
                400,
                array('subCategory' => ''),
                array(
                    'errors.children.subCategory.errors.0' => 'Заполните это поле'
                ),
            ),
            /***********************************************************************************************
             * 'unit'
             ***********************************************************************************************/
            'valid units' => array(
                201,
                array(
                    'units' => 'Штуки',
                ),
                array(
                    'units' => 'Штуки'
                )
            ),
            'empty units' => array(
                201,
                array(
                    'units' => '',
                ),
                array(
                    'units' => null
                )
            ),
            'valid units length 50' => array(
                201,
                array(
                    'units' => str_repeat('z', 50),
                ),
                array(
                    'units' => str_repeat('z', 50),
                )
            ),
            'invalid units max length 50' => array(
                400,
                array(
                    'units' => str_repeat('z', 51),
                ),
                array(
                    'errors.children.units.errors.0' => 'Не более 50 символов'
                )
            ),
            /***********************************************************************************************
             * 'type'
             ***********************************************************************************************/
            'invalid type' => array(
                400,
                array(
                    'type' => 'invalid type',
                    'typeProperties' => array(
                        'nameOnScales' => '',
                        'descriptionOnScales' => '',
                        'shelfLife' => '',
                        'ingredients' => '',
                    )
                ),
                array(
                    'errors.children.type.errors.0' => 'Выбранное Вами значение недопустимо.',
                    'errors.errors.0' => 'Эта форма не должна содержать дополнительных полей: "typeProperties"',
                )
            ),
            'empty type' => array(
                400,
                array(
                    'type' => ''
                ),
                array(
                    'errors.children.type.errors.0' => 'Заполните это поле'
                )
            ),
            /***********************************************************************************************
             * 'weightType'
             ***********************************************************************************************/
            'weight type empty fields' => array(
                201,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'nameOnScales' => '',
                        'descriptionOnScales' => '',
                        'shelfLife' => '',
                        'ingredients' => '',
                    )
                ),
                array(
                    'typeUnits' => WeightType::UNITS,
                    'type' => WeightType::TYPE,
                    'typeProperties' => array()
                )
            ),
            'weight type valid fields' => array(
                201,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'nameOnScales' => 'Наименование на весах',
                        'descriptionOnScales' => 'Описание на весах',
                        'shelfLife' => '24',
                        'ingredients' => 'Лук, чеснок, соль',
                        'nutritionFacts' => "   Углеводы 5гр,\n  Белки 10гр\n  Жиры 20гр  \n"
                    )
                ),
                array(
                    'typeUnits' => WeightType::UNITS,
                    'type' => WeightType::TYPE,
                    'typeProperties.nameOnScales' => 'Наименование на весах',
                    'typeProperties.descriptionOnScales' => 'Описание на весах',
                    'typeProperties.shelfLife' => '24',
                    'typeProperties.ingredients' => 'Лук, чеснок, соль',
                    'typeProperties.nutritionFacts' => "Углеводы 5гр,\n  Белки 10гр\n  Жиры 20гр"
                )
            ),
            'weight type fields valid max length' => array(
                201,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'nameOnScales' => str_repeat('z', 256),
                        'descriptionOnScales' => str_repeat('z', 256),
                        'shelfLife' => '24',
                        'ingredients' => str_repeat('z', 1024),
                        'nutritionFacts' => str_repeat('z', 1024),
                    )
                ),
                array(
                    'typeUnits' => WeightType::UNITS,
                    'type' => WeightType::TYPE,
                    'typeProperties.nameOnScales' => str_repeat('z', 256),
                    'typeProperties.descriptionOnScales' => str_repeat('z', 256),
                    'typeProperties.shelfLife' => '24',
                    'typeProperties.ingredients' => str_repeat('z', 1024),
                    'typeProperties.nutritionFacts' => str_repeat('z', 1024)
                )
            ),
            'weight type fields invalid length' => array(
                400,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'nameOnScales' => str_repeat('z', 257),
                        'descriptionOnScales' => str_repeat('z', 257),
                        'shelfLife' => '24',
                        'ingredients' => str_repeat('z', 1025),
                        'nutritionFacts' => str_repeat('z', 1025),
                    )
                ),
                array(
                    'typeUnits' => null,
                    'type' => null,
                    'errors.children.typeProperties.children.nameOnScales.errors.0' => 'Не более 256 символов',
                    'errors.children.typeProperties.children.descriptionOnScales.errors.0' => 'Не более 256 символов',
                    'errors.children.typeProperties.children.shelfLife.errors' => null,
                    'errors.children.typeProperties.children.ingredients.errors.0' => 'Не более 1024 символов',
                    'errors.children.typeProperties.children.nutritionFacts.errors.0' => 'Не более 1024 символов'
                )
            ),
            'weight type invalid shelLife not a number' => array(
                400,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'shelfLife' => 'aaa',
                    )
                ),
                array(
                    'typeUnits' => null,
                    'type' => null,
                    'errors.children.typeProperties.children.shelfLife.errors.0' => 'Значение должно быть числом',
                )
            ),
            'weight type invalid shelLife float' => array(
                400,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'shelfLife' => '12.12',
                    )
                ),
                array(
                    'typeUnits' => null,
                    'type' => null,
                    'errors.children.typeProperties.children.shelfLife.errors.0' => 'Значение должно быть числом',
                )
            ),
            'weight type invalid shelLife too big' => array(
                400,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'shelfLife' => '1001',
                    )
                ),
                array(
                    'typeUnits' => null,
                    'type' => null,
                    'errors.children.typeProperties.children.shelfLife.errors.0'
                    =>
                    'Значение должно быть меньше или равно 1000',
                )
            ),
            'weight type invalid shelLife too big extra field' => array(
                400,
                array(
                    'type' => WeightType::TYPE,
                    'typeProperties' => array(
                        'shelfLife' => '24',
                        'bestBefore' => '2014.02.11'
                    )
                ),
                array(
                    'typeUnits' => null,
                    'type' => null,
                    'errors.errors.0' => 'Эта форма не должна содержать дополнительных полей: "bestBefore"',
                )
            ),
            /***********************************************************************************************
             * 'unitType'
             ***********************************************************************************************/
            'unit type empty fields' => array(
                201,
                array(
                    'type' => UnitType::TYPE,
                    'typeProperties' => array(
                    )
                ),
                array(
                    'typeUnits' => UnitType::UNITS,
                    'type' => UnitType::TYPE,
                    'typeProperties' => array()
                )
            ),
            'unit type extra field' => array(
                400,
                array(
                    'type' => UnitType::TYPE,
                    'typeProperties' => array(
                        'field' => 'value'
                    )
                ),
                array(
                    'typeUnits' => null,
                    'type' => null,
                    'errors.errors.0' => 'Эта форма не должна содержать дополнительных полей: "field"',
                )
            ),
            /***********************************************************************************************
             * 'alcoholType'
             ***********************************************************************************************/
            'alcohol type empty fields' => array(
                201,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                    )
                ),
                array(
                    'typeUnits' => AlcoholType::UNITS,
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array()
                )
            ),
            'alcohol type valid fields coma' => array(
                201,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '38,5',
                        'volume' => '0,375',
                    )
                ),
                array(
                    'typeUnits' => AlcoholType::UNITS,
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '38.5',
                        'volume' => '0.375',
                    )
                )
            ),
            'alcohol type valid fields dot' => array(
                201,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '38.5',
                        'volume' => '0.375',
                    )
                ),
                array(
                    'typeUnits' => AlcoholType::UNITS,
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '38.5',
                        'volume' => '0.375',
                    )
                )
            ),
            'alcohol type invalid alcoholByVolume' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => 'aaa',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.alcoholByVolume.errors.0' => 'Значение должно быть числом',
                )
            ),
            'alcohol type alcoholByVolume equals 0' => array(
                201,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '0',
                    )
                ),
                array(
                    'typeProperties.alcoholByVolume' => '0',
                )
            ),
            'alcohol type alcoholByVolume less than 0' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '-0.1',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.alcoholByVolume.errors.0'
                    =>
                    'Значение должно быть больше или равно 0',
                )
            ),
            'alcohol type alcoholByVolume equals 99.9' => array(
                201,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '99.9',
                    )
                ),
                array(
                    'typeProperties.alcoholByVolume' => '99.9',
                )
            ),
            'alcohol type alcoholByVolume equals 100' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '100',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.alcoholByVolume.errors.0'
                    =>
                    'Значение должно быть меньше 100',
                )
            ),
            'alcohol type alcoholByVolume precision 1' => array(
                201,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '99.9',
                    )
                ),
                array(
                    'typeProperties.alcoholByVolume' => '99.9'
                )
            ),
            'alcohol type alcoholByVolume precision 2' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'alcoholByVolume' => '-0.01',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.alcoholByVolume.errors.0'
                    =>
                    'Значение не должно содержать больше 1 цифр после запятой',
                )
            ),
            'alcohol type invalid volume' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'volume' => 'bbb',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.volume.errors.0' => 'Значение должно быть числом',
                )
            ),
            'alcohol type volume equals 0' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'volume' => '0',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.volume.errors.0' => 'Значение должно быть больше 0',
                )
            ),
            'alcohol type volume less than 0' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'volume' => '-0.01',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.volume.errors.0' => 'Значение должно быть больше 0',
                )
            ),
            'alcohol type volume precision 3' => array(
                201,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'volume' => '100.001',
                    )
                ),
                array(
                    'typeProperties.volume' => '100.001'
                )
            ),
            'alcohol type volume precision 4' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'volume' => '100.0001',
                    )
                ),
                array(
                    'errors.children.typeProperties.children.volume.errors.0'
                    =>
                    'Значение не должно содержать больше 3 цифр после запятой',
                )
            ),
            'alcohol type extra field' => array(
                400,
                array(
                    'type' => AlcoholType::TYPE,
                    'typeProperties' => array(
                        'field' => 'value'
                    )
                ),
                array(
                    'typeUnits' => null,
                    'type' => null,
                    'errors.errors.0' => 'Эта форма не должна содержать дополнительных полей: "field"',
                )
            ),
        );
    }

    /**
     * @dataProvider validRetailPriceProvider
     * @param array $postData
     * @param array $assertions
     */
    public function testPostProductActionSetRetailsPriceValid(array $postData, array $assertions = array())
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory();
        $postData['subCategory'] = $subCategory->id;

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
     * @param array $postData
     * @param array $assertions
     */
    public function testPostProductActionSetRetailsPriceInvalid(array $postData, array $assertions = array())
    {
        $this->markTestSkipped('Min Max Retail Price is disabled');
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
     * @param array $putData
     * @param array $assertions
     */
    public function testPutProductActionSetRetailPriceValid(array $putData, array $assertions = array())
    {
        $postData = $this->getProductData();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
     * @param array $putData
     * @param array $assertions
     */
    public function testPutProductActionSetRetailPriceInvalid(array $putData, array $assertions = array())
    {
        $this->markTestSkipped('Min Max Retail Price is disabled');
        $postData = $this->getProductData();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
                    'errors.children.retailPriceMax.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
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
                    'errors.children.retailPriceMin.errors.0'
                    =>
                    'Цена продажи должна быть больше или равна цене закупки',
                    'errors.children.retailMarkupMin.errors'
                    =>
                    null
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
                    'errors.children.retailPriceMin.errors.0' => 'Цена не должна быть меньше или равна нулю',
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
                    'errors.children.retailPriceMin.errors.0'
                    =>
                    'Минимальная цена продажи не должна быть больше максимальной',
                    'errors.children.retailPriceMax.errors'
                    =>
                    null,
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
                    'errors.children.retailMarkupMin.errors.0' => 'Наценка должна быть равна или больше 0%',
                    'errors.children.retailMarkupMin.errors.1' => null,
                    'errors.children.retailMarkupMax.errors' => null,
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
                    'errors.children.retailMarkupMin.errors.0' => 'Наценка должна быть равна или больше 0%',
                    'errors.children.retailMarkupMin.errors.1' => null,
                    'errors.children.retailMarkupMax.errors' => null,
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
                    'errors.children.retailMarkupMin.errors.0' => 'Значение должно быть числом',
                    'errors.children.retailMarkupMin.errors.1' => null,
                    'errors.children.retailMarkupMax.errors' => null,
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
                    'errors.children.retailMarkupMin.errors.0'
                    =>
                    'Значение не должно содержать больше 2 цифр после запятой',
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
                    'errors.children.retailPriceMin.errors.0' => 'Значение должно быть числом',
                    'errors.children.retailPriceMin.errors.1' => null,
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
                    'errors.children.retailPriceMin.errors.0' => 'Значение должно быть числом',
                    'errors.children.purchasePrice.errors.0' => null,
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
                    'errors.children.retailPriceMin.errors.0' => 'Значение должно быть числом',
                    'errors.children.retailPriceMax.errors.0' => 'Значение должно быть числом',
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
                    'errors.children.retailMarkupMin.errors.0' => 'Значение должно быть числом',
                    'errors.children.retailPriceMin.errors' => null,
                    'errors.children.purchasePrice.errors' => null
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
                    'errors.children.retailMarkupMin.errors.0'
                    =>
                    'Минимальная наценка не должна быть больше максимальной',
                    'errors.children.retailPriceMin.errors' => null,
                    'errors.children.retailMarkupMax.errors' => null,
                    'errors.children.retailPriceMax.errors' => null,
                    'errors.children.purchasePrice.errors' => null
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
                    'errors.children.retailPriceMin.errors.0'
                    =>
                    'Минимальная цена продажи не должна быть больше максимальной',
                    'errors.children.retailMarkupMin.errors' => null,
                    'errors.children.retailMarkupMax.errors' => null,
                    'errors.children.retailPriceMax.errors' => null,
                    'errors.children.purchasePrice.errors' => null
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
                    'errors.children.retailPriceMax.errors.0' => 'Заполните это поле',
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
                    'errors.children.retailPriceMin.errors.0' => 'Заполните это поле',
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
                    'errors.children.retailMarkupMax.errors.0' => 'Заполните это поле',
                    'errors.children.retailMarkupMax.errors.1' => null,
                    'errors.children.retailMarkupMin.errors' => null,
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
                    'errors.children.retailMarkupMin.errors.0' => 'Заполните это поле',
                    'errors.children.retailMarkupMin.errors.1' => null,
                    'errors.children.retailMarkupMax.errors' => null,
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
                    'errors.children.retailPriceMin.errors.0'
                    =>
                    'Нельзя ввести цену продажи при отсутствии закупочной цены',
                    'errors.children.retailPriceMin.errors.1'
                    =>
                    null,
                    'errors.children.retailPriceMax.errors.0'
                    =>
                    'Нельзя ввести цену продажи при отсутствии закупочной цены',
                    'errors.children.retailPriceMax.errors.1'
                    =>
                    null,
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
                    'errors.children.retailMarkupMin.errors.0'
                    =>
                    'Нельзя ввести наценку при отсутствии закупочной цены',
                    'errors.children.retailMarkupMin.errors.1'
                    =>
                    null,
                    'errors.children.retailMarkupMax.errors.0'
                    =>
                    'Нельзя ввести наценку при отсутствии закупочной цены',
                    'errors.children.retailMarkupMax.errors.1'
                    =>
                    null,
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
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                    'errors.children.purchasePrice.errors.1' => null,
                    'errors.children.retailPriceMin.errors.0' => null,
                    'errors.children.retailPriceMax.errors.0' => null,
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
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                    'errors.children.purchasePrice.errors.1' => null,
                    'errors.children.retailMarkupMin.errors.0' => null,
                    'errors.children.retailMarkupMax.errors.0' => null,
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
        $product = $this->factory()->catalog()->getProduct();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $putData += $this->getProductData();

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/products/{$product->id}",
            $putData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions);

        if (200 == $expectedCode) {
            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                "/api/1/products/{$product->id}"
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
                    'errors.children.rounding.errors.0' => 'Значение недопустимо.',
                )
            ),
            'invalid 0 rounding' => array(
                400,
                array(
                    'rounding' => 0,
                ),
                array(
                    'errors.children.rounding.errors.0' => 'Значение недопустимо.',
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
            $subCategory = $this->factory()->catalog()->getSubCategory();
            $productData['milkman'][0]['subCategory'] = $subCategory->id;
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
        $subCategory = $this->factory()->catalog()->getSubCategory('Пиво');
        $product = $this->factory()->catalog()->getProduct('Старый мельник', $subCategory);

        $url = str_replace(
            array(
                '__PRODUCT_ID__',
                '__SUBCATEGORY_ID__',
            ),
            array(
                $product->id,
                $subCategory->id,
            ),
            $url
        );
        $accessToken = $this->factory()->oauth()->authAsRole($role);
        $requestData += $this->getProductData();

        $this->client->setCatchException();
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
            'GET COMMERCIAL MANAGER' => array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            'GET DEPARTMENT MANAGER' => array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                200,
            ),
            'GET STORE MANAGER' => array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                User::ROLE_STORE_MANAGER,
                200,
            ),
            'GET ADMINISTRATOR' => array(
                '/api/1/products/__PRODUCT_ID__',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
            /*************************************
             * POST /api/1/products
             */
            'POST COMMERCIAL MANAGER' => array(
                '/api/1/products',
                'POST',
                User::ROLE_COMMERCIAL_MANAGER,
                201,
            ),
            'POST DEPARTMENT MANAGER' => array(
                '/api/1/products',
                'POST',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            'POST STORE MANAGER' => array(
                '/api/1/products',
                'POST',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            'POST ADMINISTRATOR' => array(
                '/api/1/products',
                'POST',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
            /*************************************
             * PUT /api/1/products/__PRODUCT_ID__
             */
            'PUT COMMERCIAL MANAGER' => array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            'PUT DEPARTMENT MANAGER' => array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            'PUT STORE MANAGER' => array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            'PUT ADMINISTRATOR' => array(
                '/api/1/products/__PRODUCT_ID__',
                'PUT',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
            /*************************************
             * GET /api/1/subcategories/__SUBCATEGORY_ID__/products
             */
            'GET SUBCATEGORY COMMERCIAL MANAGER' => array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            'GET SUBCATEGORY DEPARTMENT MANAGER' => array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                200,
            ),
            'GET SUBCATEGORY STORE MANAGER' => array(
                '/api/1/subcategories/__SUBCATEGORY_ID__/products',
                'GET',
                User::ROLE_STORE_MANAGER,
                200,
            ),
            'GET SUBCATEGORY ADMINISTRATOR' => array(
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

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequests = array();
        for ($i = 0; $i <= 2; $i++) {
            $data = array('barcode' => $i) + $productData;
            $jsonRequest = new JsonRequest('/api/1/products', 'POST', $data);
            $jsonRequest->setAccessToken($accessToken);
            $jsonRequests[] = $jsonRequest;
        }

        $responses = $this->client->parallelJsonRequests($jsonRequests);
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

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
        $productRepoMock
            ->expects($this->once())
            ->method('findByBarcodes')
            ->will($this->returnValue(array()));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($productRepoMock) {
                $container->set('lighthouse.core.document.repository.product', $productRepoMock);
            }
        );

        $this->client->setCatchException();
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
        Assert::assertJsonPathEquals('Такой артикул уже есть', 'errors.errors.0', $response);
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

    public function testDeleteAction()
    {
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2'));

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/products/{$products['1']->id}"
        );

        $this->assertResponseCode(204);

        $this->assertNull($deleteResponse);

        // assert product is accessible by direct link
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/products/{$products['1']->id}"
        );

        $this->assertResponseCode(200);
        $this->assertNotEquals('1', $getResponse['name']);
        $this->assertContains('Удалено', $getResponse['name']);
    }

    public function testDeleteProductIsNotVisibleInProductsList()
    {
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2'));

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/products/{$products['1']->id}"
        );

        $this->assertResponseCode(204);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals($products['2']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($products['1']->id, '*.id', $getResponse);
    }

    public function testDeleteProductIsNotVisibleInSubCategoryProductsList()
    {
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2'));

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/products/{$products['1']->id}"
        );

        $this->assertResponseCode(204);

        // assert product is not visible in sub category products list
        $subCategory = $this->factory()->catalog()->getSubCategory();
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            "/api/1/subcategories/{$subCategory->id}/products"
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals($products['2']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($products['1']->id, '*.id', $getResponse);
    }

    public function testDeleteProductIsNotVisibleInProductSearch()
    {
        $products = $this->factory()->catalog()->getProductByNames(array('Каша овсяная', 'Каша гречневая'));

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/products/{$products['Каша овсяная']->id}"
        );

        $this->assertResponseCode(204);

        $query = array(
            'properties' => array('name'),
            'query' => 'Каша'
        );

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/products/search',
            array(),
            $query
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals($products['Каша гречневая']->id, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($products['Каша овсяная']->id, '*.id', $getResponse);
    }

    /**
     * @dataProvider setSellingPriceProvider
     * @param array $postData
     * @param int $expectedResponseCode
     * @param array $assertions
     */
    public function testPostProductActionSetSellingPrice(
        array $postData,
        $expectedResponseCode,
        array $assertions = array()
    ) {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $productData = $postData + $this->getProductData(true);

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $productData
        );

        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @dataProvider setSellingPriceProvider
     * @param array $putData
     * @param int $expectedResponseCode
     * @param array $assertions
     */
    public function testPutProductActionSetSellingPrice(
        array $putData,
        $expectedResponseCode,
        array $assertions = array()
    ) {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = $this->factory()->catalog()->getSubCategory('Пиво');
        $postData = array(
            'name' => 'Продукт',
            'sellingPrice' => '45.89',
        );
        $product = $this->factory()->catalog()->createProduct($postData, $subCategory);

        $productData = $putData + $this->getProductData(true);

        $response = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            "/api/1/products/{$product->id}",
            $productData
        );

        $expectedResponseCode = (201 == $expectedResponseCode) ? 200 : $expectedResponseCode;

        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($response, $assertions);
    }

    /**
     * @return array
     */
    public function setSellingPriceProvider()
    {
        return array(
            'sellingPrice and purchasePrice' => array(
                array(
                    'purchasePrice' => '30.48',
                    'sellingPrice' => '35',
                ),
                201,
                array(
                    'purchasePrice' => '30.48',
                    'sellingPrice' => '35.00',
                    'sellingMarkup' => '14.83',
                    'retailPricePreference' => Product::RETAIL_PRICE_PREFERENCE_PRICE,
                    'retailPriceMin' => '35.00',
                    'retailPriceMax' => '35.00',
                    'retailMarkupMin' => '14.83',
                    'retailMarkupMax' => '14.83',
                )
            ),
            'sellingPrice and empty purchasePrice' => array(
                array(
                    'purchasePrice' => '',
                    'sellingPrice' => '35',
                ),
                201,
                array(
                    'purchasePrice' => null,
                    'sellingPrice' => '35.00',
                    'sellingMarkup' => null,
                    'retailPricePreference' => Product::RETAIL_PRICE_PREFERENCE_PRICE,
                    'retailPriceMin' => '35.00',
                    'retailPriceMax' => '35.00',
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                )
            ),
            'empty sellingPrice and empty purchasePrice' => array(
                array(
                    'purchasePrice' => '',
                    'sellingPrice' => '',
                ),
                201,
                array(
                    'purchasePrice' => null,
                    'sellingPrice' => null,
                    'sellingMarkup' => null,
                    'retailPricePreference' => Product::RETAIL_PRICE_PREFERENCE_MARKUP,
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                )
            ),
            'empty sellingPrice and purchasePrice' => array(
                array(
                    'purchasePrice' => '30.48',
                    'sellingPrice' => '',
                ),
                201,
                array(
                    'purchasePrice' => '30.48',
                    'sellingPrice' => null,
                    'sellingMarkup' => null,
                    'retailPricePreference' => Product::RETAIL_PRICE_PREFERENCE_MARKUP,
                    'retailPriceMin' => null,
                    'retailPriceMax' => null,
                    'retailMarkupMin' => null,
                    'retailMarkupMax' => null,
                )
            ),
            'invalid sellingPrice and purchasePrice have more than 3 digits after coma' => array(
                array(
                    'purchasePrice' => '30.482',
                    'sellingPrice' => '35.001',
                ),
                400,
                array(
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                    'errors.children.sellingPrice.errors.0' => 'Цена не должна содержать больше 2 цифр после запятой',
                )
            ),
            'invalid sellingPrice and purchasePrice equals 0' => array(
                array(
                    'purchasePrice' => '0.000',
                    'sellingPrice' => '0.0',
                ),
                400,
                array(
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна быть меньше или равна нулю',
                    'errors.children.sellingPrice.errors.0' => 'Цена не должна быть меньше или равна нулю',
                )
            ),
            'invalid sellingPrice and purchasePrice less than 0' => array(
                array(
                    'purchasePrice' => '-0.01',
                    'sellingPrice' => '-15',
                ),
                400,
                array(
                    'errors.children.purchasePrice.errors.0' => 'Цена не должна быть меньше или равна нулю',
                    'errors.children.sellingPrice.errors.0' => 'Цена не должна быть меньше или равна нулю',
                )
            ),
            'invalid sellingPrice and purchasePrice not a number' => array(
                array(
                    'purchasePrice' => '12.as',
                    'sellingPrice' => '#90',
                ),
                400,
                array(
                    'errors.children.purchasePrice.errors.0' => 'Значение должно быть числом',
                    'errors.children.sellingPrice.errors.0' => 'Значение должно быть числом',
                )
            )
        );
    }

    /**
     * @return ProductRepository
     */
    protected function getProductRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.product');
    }
}
