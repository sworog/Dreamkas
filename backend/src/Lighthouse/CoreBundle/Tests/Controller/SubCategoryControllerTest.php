<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use PHPUnit_Util_Type;
use MongoDuplicateKeyException;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubCategoryControllerTest extends WebTestCase
{
    public function testPostSubCategoriesAction()
    {
        $groupId = $this->createGroup('Алкоголь');
        $categoryId = $this->createCategory($groupId, 'Крепкий алкоголь');

        $subCategoryData = array(
            'name' => 'Водка',
            'category' => $categoryId,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('Водка', 'name', $postResponse);
        Assert::assertJsonPathEquals($categoryId, 'category.id', $postResponse);
        Assert::assertJsonPathEquals('Крепкий алкоголь', 'category.name', $postResponse);
        Assert::assertJsonPathEquals($groupId, 'category.group.id', $postResponse);
        Assert::assertJsonPathEquals('Алкоголь', 'category.group.name', $postResponse);
    }

    public function testPutSubCategoryActionRoundingUpdated()
    {
        $groupId = $this->createGroup('Алкоголь');
        $categoryId = $this->createCategory($groupId, 'Водка');
        $subCategoryId = $this->createSubCategory($categoryId, 'Безалкогольная водка');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest1', 'rounding.name', $getResponse);

        $subCategoryData = array(
            'name' => 'Алкоголь',
            'category' => $categoryId,
            'rounding' => 'nearest50',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/subcategories/' . $subCategoryId,
            $subCategoryData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', '0.rounding.name', $getResponse);
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationSubCategoryProvider
     */
    public function testPostSubCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $groupId = $this->createGroup('Алкоголь');
        $categoryId = $this->createCategory($groupId, 'Крепкий алкоголь');

        $subCategoryData = $data + array(
            'name' => 'Водка',
            'category' => $categoryId,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationSubCategoryProvider()
    {
        return array(
            'not valid empty name' => array(
                400,
                array('name' => ''),
                array(
                    'children.name.errors.0'
                    =>
                    'Заполните это поле'
                )
            ),
            'not valid long 101 name' => array(
                400,
                array('name' => str_repeat('z', 101)),
                array(
                    'children.name.errors.0'
                    =>
                    'Не более 100 символов'
                )
            ),
            'not valid category' => array(
                400,
                array('category' => '1234'),
                array(
                    'children.category.errors.*'
                    =>
                    'Такой категории не существует'
                )
            ),
            'valid long 100 name' => array(
                201,
                array('name' => str_repeat('z', 100)),
            ),
            // retail markup
            'valid markup' => array(
                201,
                array('retailMarkupMin' => 10.01, 'retailMarkupMax' => 20.13),
            ),
            'valid markup lower boundary' => array(
                201,
                array('retailMarkupMin' => 0, 'retailMarkupMax' => 1000),
            ),
            'valid markup min equals max' => array(
                201,
                array('retailMarkupMin' => 10.12, 'retailMarkupMax' => 10.12),
            ),
            'not valid markup -0.01' => array(
                400,
                array('retailMarkupMin' => -0.01, 'retailMarkupMax' => 100),
                array('children.retailMarkupMin.errors.0' => 'Значение должно быть больше или равно 0')
            ),
            'not valid markup min is more than max' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => 0),
                array('children.retailMarkupMin.errors.0' => 'Минимальная наценка не может быть больше максимальной')
            ),
            'not valid markup not float' => array(
                400,
                array('retailMarkupMin' => 'aaa', 'retailMarkupMax' => 'bbb'),
                array('children.retailMarkupMin.errors.*' => 'Значение должно быть числом'),
                array('children.retailMarkupMax.errors.*' => 'Значение должно быть числом')
            ),
            'not valid markup min not float' => array(
                400,
                array('retailMarkupMin' => 'aaa', 'retailMarkupMax' => 10),
                array(
                    'children.retailMarkupMin.errors.0' => 'Значение должно быть числом',
                    'children.retailMarkupMin.errors.1' => null,
                ),
            ),
            'not valid markup max not float' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => 'bbb'),
                array(
                    'children.retailMarkupMax.errors.0' => 'Значение должно быть числом',
                    'children.retailMarkupMin.errors.1' => null,
                    'children.retailMarkupMin.errors' => null,
                ),
            ),
            'not valid markup, min entered, max not' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => ''),
                array('children.retailMarkupMax.errors.0' => 'Заполните это поле'),
            ),
            'not valid markup, max entered, min not' => array(
                400,
                array('retailMarkupMin' => '', 'retailMarkupMax' => 10),
                array('children.retailMarkupMin.errors.0' => 'Заполните это поле'),
            ),
            // rounding
            'valid rounding nearest1' => array(
                201,
                array('rounding' => 'nearest1'),
                array('rounding.name' => 'nearest1', 'rounding.title' => 'до копеек')
            ),
            'valid rounding nearest10' => array(
                201,
                array('rounding' => 'nearest10'),
                array('rounding.name' => 'nearest10', 'rounding.title' => 'до 10 копеек')
            ),
            'valid rounding nearest50' => array(
                201,
                array('rounding' => 'nearest50'),
                array('rounding.name' => 'nearest50', 'rounding.title' => 'до 50 копеек')
            ),
            'valid rounding nearest100' => array(
                201,
                array('rounding' => 'nearest100'),
                array('rounding.name' => 'nearest100', 'rounding.title' => 'до рублей')
            ),
            'valid rounding nearest99' => array(
                201,
                array('rounding' => 'nearest99'),
                array('rounding.name' => 'nearest99', 'rounding.title' => 'до 99 копеек')
            ),
            'invalid rounding aaaa' => array(
                400,
                array('rounding' => 'aaaa'),
                array(
                    'children.rounding.errors.0' => 'Значение недопустимо.',
                )
            ),
            'valid rounding no value, should inherit group value' => array(
                201,
                array('rounding' => null),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
            'valid rounding empty value, should inherit group value' => array(
                201,
                array('rounding' => ''),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
        );
    }

    public function testUniqueCategoryName()
    {
        $groupId1 = $this->createGroup('Алкоголь');
        $groupId2 = $this->createGroup('Кисло-молочка');
        $categoryId1 = $this->createCategory($groupId1, 'Крепкий алкоголь');
        $categoryId2 = $this->createCategory($groupId2, 'Молоко');

        $subCategoryData = array(
            'name' => 'Водка',
            'category' => $categoryId1,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        // Create first category
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Try to create second category with same name in group 1
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );
        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Подкатегория с таким названием уже существует в этой категории',
            'children.name.errors',
            $postResponse
        );

        $subCategoryData2 = array('category' => $categoryId2) + $subCategoryData;

        // Create category with same name but in category 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData2
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        // Create second category with same name in category 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData2
        );
        $this->assertResponseCode(400);

        Assert::assertJsonPathContains(
            'Подкатегория с таким названием уже существует в этой категории',
            'children.name.errors',
            $postResponse
        );
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationSubCategoryProvider
     */
    public function testPutSubCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $groupId = $this->createGroup('Алкоголь');
        $categoryId = $this->createCategory($groupId, 'Крепкий алкоголь');

        $postData = array(
            'name' => 'Водка',
            'category' => $categoryId,
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = $data + $postData;

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/subcategories/' . $subCategoryId,
            $putData
        );

        $expectedCode = (201 == $expectedCode) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions, true);
    }

    public function testGetCategory()
    {
        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);
        $subCategoryId = $this->createSubCategory($categoryId);

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($subCategoryId, 'id', $getResponse);
        Assert::assertJsonPathEquals($categoryId, 'category.id', $getResponse);
        Assert::assertJsonPathEquals($groupId, 'category.group.id', $getResponse);
    }

    public function testGetCategoryNotFound()
    {
        $groupId1 = $this->createGroup('1');
        $categoryId = $this->createCategory($groupId1, '1.1');
        $this->createSubCategory($categoryId, '1.1.1');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/invalidId'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategories()
    {
        $groupId1 = $this->createGroup('1');
        $groupId2 = $this->createGroup('2');

        $categoryId1 = $this->createCategory($groupId1, '1.1');
        $categoryId2 = $this->createCategory($groupId2, '2.1');

        $subCategoryId1 = $this->createSubCategory($categoryId1, '1.1.1');
        $subCategoryId2 = $this->createSubCategory($categoryId1, '1.1.2');
        $subCategoryId3 = $this->createSubCategory($categoryId1, '1.1.3');

        $subCategoryId4 = $this->createSubCategory($categoryId2, '2.1.4');
        $subCategoryId5 = $this->createSubCategory($categoryId2, '2.1.5');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId1 . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategoryId1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategoryId2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategoryId3, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($subCategoryId4, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($subCategoryId5, '*.id', $getResponse);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId2 . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategoryId4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategoryId5, '*.id', $getResponse, 1);
        Assert::assertNotJsonPathEquals($subCategoryId1, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($subCategoryId2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($subCategoryId3, '*.id', $getResponse);
    }

    public function testGetCategoriesNotFound()
    {
        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/123484923423/subcategories'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategoriesEmptyCollection()
    {
        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testDeleteCategory()
    {
        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);
        $subCategoryId = $this->createSubCategory($categoryId);

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(204);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(404);
    }

    public function testDeleteNotEmptyCategory()
    {
        $subCategoryId = $this->createSubCategory();

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        $productId = $this->createProduct('', $subCategoryId);

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId . '/products'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($productId, '0.id', $response);

        $response = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(409);
        Assert::assertJsonHasPath('message', $response);
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array     $requestData
     *
     * @dataProvider accessSubCategoryProvider
     */
    public function testAccessSubCategory($url, $method, $role, $responseCode, array $requestData = array())
    {
        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);
        $subCategoryId = $this->createSubCategory($categoryId);

        $url = str_replace(
            array(
                '__SUBCATEGORY_ID__',
                '__CATEGORY_ID__',
            ),
            array(
                $subCategoryId,
                $categoryId,
            ),
            $url
        );

        $accessToken = $this->factory->oauth()->authAsRole($role);

        $requestData += array(
            'name' => 'Тёмное',
            'category' => $categoryId,
            'rounding' => 'nearest1',
        );

        $this->clientJsonRequest(
            $accessToken,
            $method,
            $url,
            $requestData
        );

        $this->assertResponseCode($responseCode);
    }

    public function accessSubCategoryProvider()
    {
        return array(
            /*************************************
             * GET /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * POST /api/1/subcategories
             */
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_COMMERCIAL_MANAGER,
                201,
            ),
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories',
                'POST',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * PUT /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * DELETE /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_COMMERCIAL_MANAGER,
                204,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                User::ROLE_ADMINISTRATOR,
                403,
            ),

            /*************************************
             * GET /api/1/categories/__CATEGORY_ID__/subcategories
             */
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_COMMERCIAL_MANAGER,
                200,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_DEPARTMENT_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_STORE_MANAGER,
                403,
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                User::ROLE_ADMINISTRATOR,
                403,
            ),
        );
    }

    public function testRetailMarkupIsNullOnSubCategoryCreateWithEmptyMarkup()
    {
        $groupId = $this->factory->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory->catalog()->createCategory($groupId, 'Вино')->id;
        $subCategoryId = $this->factory->catalog()->createSubCategory($categoryId, 'Сухое красное божоле')->id;

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $subCategoryResponse);
    }

    public function testRetailMarkupIsNotInheritedFromGroupAfterGroupUpdate()
    {
        $groupId = $this->factory->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory->catalog()->createCategory($groupId, 'Вино')->id;
        $subCategoryId = $this->factory->catalog()->createSubCategory($categoryId, 'Сухое красное божоле')->id;

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $subCategoryResponse);

        $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/groups/' . $groupId,
            array(
                'name' => 'Алкоголь',
                'rounding' => 'nearest1',
                'retailMarkupMin' => 15,
                'retailMarkupMax' => 25,
            )
        );

        $this->assertResponseCode(200);

        $subCategoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $subCategoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $subCategoryResponse);
    }

    public function testRetailMarkupBecomesNullIfNullMarkupPassed()
    {
        $groupId = $this->factory->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory->catalog()->createCategory($groupId, 'Вино')->id;

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'category' => $categoryId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Бужуле ново',
            'category' => $categoryId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => null,
            'retailMarkupMax' => null,
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/subcategories/' . $subCategoryId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $putResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $putResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $putResponse);
    }

    public function testRetailMarkupBecomesNullIfNoMarkupPassed()
    {
        $groupId = $this->factory->catalog()->createGroup('Алкоголь', null, 10, 20)->id;
        $categoryId = $this->factory->catalog()->createCategory($groupId, 'Вино')->id;

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'category' => $categoryId,
            'rounding' => 'nearest1',
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Бужуле ново',
            'category' => $categoryId,
            'rounding' => 'nearest1',
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/subcategories/' . $subCategoryId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $putResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $putResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreSubCategoryStoreManagerHasStore($role, $rel)
    {
        $storeManager = $this->factory->user()->getUser('Василий Петрович Краузе', 'password', $role);

        $subCategoryId = $this->createSubCategory();
        $storeId = $this->factory->store()->getStoreId();

        $this->factory->store()->linkManagers($storeId, $storeManager->id, $rel);

        $accessToken = $this->factory->oauth()->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($subCategoryId, 'id', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreSubCategoryStoreManagerFromAnotherStore($role, $rel)
    {
        $storeManager = $this->factory->user()->getUser('Василий Петрович Краузе', 'password', $role);

        $subCategoryId = $this->createSubCategory();
        $storeId1 = $this->factory->store()->getStoreId('42');
        $storeId2 = $this->factory->store()->getStoreId('43');

        $this->factory->store()->linkManagers($storeId1, $storeManager->id, $rel);

        $accessToken = $this->factory->oauth()->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreSubCategoryStoreManagerHasNoStore($role)
    {
        $storeManager = $this->factory->user()->getUser('Василий Петрович Краузе', 'password', $role);

        $subCategoryId = $this->createSubCategory();
        $storeId = $this->factory->store()->getStoreId();

        $accessToken = $this->factory->oauth()->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/subcategories/' . $subCategoryId
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreCategorySubCategoriesStoreManagerHasStore($role, $rel)
    {
        $storeManager = $this->factory->user()->getUser('Василий Петрович Краузе', 'password', $role);

        $storeId = $this->factory->store()->getStoreId();

        $this->factory->store()->linkManagers($storeId, $storeManager->id, $rel);

        $groupId1 = $this->createGroup('1');
        $groupId2 = $this->createGroup('2');

        $categoryId1 = $this->createCategory($groupId1, '1.1');
        $this->createCategory($groupId1, '1.2');

        $categoryId3 = $this->createCategory($groupId2, '2.1');
        $categoryId4 = $this->createCategory($groupId2, '2.2');

        $subCategory1 = $this->createSubCategory($categoryId1, '1.1.1');
        $subCategory2 = $this->createSubCategory($categoryId1, '1.1.2');
        $subCategory3 = $this->createSubCategory($categoryId1, '1.1.3');

        $subCategory4 = $this->createSubCategory($categoryId4, '2.2.1');
        $subCategory5 = $this->createSubCategory($categoryId4, '2.2.2');

        $accessToken = $this->factory->oauth()->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/categories/' .  $categoryId1 . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategory1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategory2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategory3, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId1, '*.category.id', $getResponse, 3);
        Assert::assertJsonPathEquals($groupId1, '*.category.group.id', $getResponse, 3);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/categories/' .  $categoryId4 . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategory4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategory5, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId4, '*.category.id', $getResponse, 2);
        Assert::assertJsonPathEquals($groupId2, '*.category.group.id', $getResponse, 2);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/categories/' .  $categoryId3 . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse);
    }

    /**
     * @return array
     */
    public function storeRolesProvider()
    {
        return array(
            'store manager' => array(
                User::ROLE_STORE_MANAGER,
                Store::REL_STORE_MANAGERS
            ),
            'department manager' => array(
                User::ROLE_DEPARTMENT_MANAGER,
                Store::REL_DEPARTMENT_MANAGERS
            ),
        );
    }

    public function testRoundingIsInheritedFromGroup()
    {
        $groupId = $this->factory->catalog()->createGroup('Алкоголь', 'nearest50')->id;
        $categoryId = $this->factory->catalog()->createCategory($groupId, 'Водка', 'nearest50')->id;

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Водка',
            'category' => $categoryId,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        $subCategoryId = $postResponse['id'];

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/' . $subCategoryId,
            $postData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);
        Assert::assertJsonPathEquals('nearest50', 'category.rounding.name', $getResponse);
        Assert::assertJsonPathEquals('nearest50', 'category.group.rounding.name', $getResponse);
    }

    public function testUniqueNameInParallel()
    {
        $category = $this->factory->catalog()->getCategory();
        $subCategoryData = array(
            'name' => 'Молочка',
            'rounding' => 'nearest1',
            'category' => $category->id
        );

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/subcategories', 'POST', $subCategoryData);
        $jsonRequest->setAccessToken($accessToken);

        $responses = $this->client->parallelJsonRequest($jsonRequest, 3);
        $statusCodes = array();
        $jsonResponses = array();
        foreach ($responses as $response) {
            $statusCodes[] = $response->getStatusCode();
            $jsonResponses[] = $this->client->decodeJsonResponse($response);
        }
        $responseBody = PHPUnit_Util_Type::export($jsonResponses);
        $this->assertCount(1, array_keys($statusCodes, 201), $responseBody);
        $this->assertCount(2, array_keys($statusCodes, 400), $responseBody);
        Assert::assertJsonPathEquals('Молочка', '*.name', $jsonResponses, 1);
        Assert::assertJsonPathEquals(
            'Подкатегория с таким названием уже существует в этой категории',
            '*.children.name.errors.0',
            $jsonResponses,
            2
        );
    }

    protected function doPostActionFlushFailedException(\Exception $exception)
    {
        $category = $this->factory->catalog()->getCategory();
        $subCategoryData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1',
            'category' => $category->id
        );

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $subCategory = new SubCategory();

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

        $subCategoryRepoMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\Classifier\\SubCategory\\SubCategoryRepository',
            array(),
            array(),
            '',
            false
        );

        $subCategoryRepoMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($subCategory));
        $subCategoryRepoMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($subCategoryRepoMock) {
                $container->set('lighthouse.core.document.repository.classifier.subcategory', $subCategoryRepoMock);
            }
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/subcategories',
            $subCategoryData
        );

        return $response;
    }

    public function testPostActionFlushFailedException()
    {
        $exception = new Exception('Unknown exception');
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(500);
        Assert::assertJsonPathEquals('Unknown exception', 'message', $response);
    }

    public function testPostActionFlushFailedMongoDuplicateKeyException()
    {
        $exception = new MongoDuplicateKeyException();
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Validation Failed', 'message', $response);
        Assert::assertJsonPathEquals(
            'Подкатегория с таким названием уже существует в этой категории',
            'children.name.errors.0',
            $response
        );
    }
}
