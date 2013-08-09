<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class SubCategoryControllerTest extends WebTestCase
{
    public function testPostSubCategoriesAction()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь');
        $categoryId = $this->createCategory($groupId, 'Крепкий алкоголь');

        $subCategoryData = array(
            'name' => 'Водка',
            'category' => $categoryId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

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

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationSubCategoryProvider
     */
    public function testPostSubCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь');
        $categoryId = $this->createCategory($groupId, 'Крепкий алкоголь');

        $subCategoryData = $data + array(
            'name' => 'Водка',
            'category' => $categoryId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

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
        );
    }

    public function testUniqueCategoryName()
    {
        $this->clearMongoDb();

        $groupId1 = $this->createGroup('Алкоголь');
        $groupId2 = $this->createGroup('Кисло-молочка');
        $categoryId1 = $this->createCategory($groupId1, 'Крепкий алкоголь');
        $categoryId2 = $this->createCategory($groupId2, 'Молоко');

        $subCategoryData = array(
            'name' => 'Водка',
            'category' => $categoryId1,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
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
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь');
        $categoryId = $this->createCategory($groupId, 'Крепкий алкоголь');

        $postData = array(
            'name' => 'Водка',
            'category' => $categoryId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

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
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);
        $subCategoryId = $this->createSubCategory($categoryId);

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
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
        $this->clearMongoDb();

        $groupId1 = $this->createGroup('1');
        $categoryId = $this->createCategory($groupId1, '1.1');
        $this->createSubCategory($categoryId, '1.1.1');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/subcategories/invalidId'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategories()
    {
        $this->clearMongoDb();

        $groupId1 = $this->createGroup('1');
        $groupId2 = $this->createGroup('2');

        $categoryId1 = $this->createCategory($groupId1, '1.1');
        $categoryId2 = $this->createCategory($groupId2, '2.1');

        $subCategoryId1 = $this->createSubCategory($categoryId1, '1.1.1');
        $subCategoryId2 = $this->createSubCategory($categoryId1, '1.1.2');
        $subCategoryId3 = $this->createSubCategory($categoryId1, '1.1.3');

        $subCategoryId4 = $this->createSubCategory($categoryId2, '2.1.4');
        $subCategoryId5 = $this->createSubCategory($categoryId2, '2.1.5');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
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
        Assert::assertJsonPathEquals($subCategoryId4, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($subCategoryId5, '*.id', $getResponse, false);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId2 . '/subcategories'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($subCategoryId4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategoryId5, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($subCategoryId1, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($subCategoryId2, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($subCategoryId3, '*.id', $getResponse, false);
    }

    public function testGetCategoriesNotFound()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/123484923423/subcategories'
        );

        $this->assertResponseCode(404);
    }

    public function testGetCategoriesEmptyCollection()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

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
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);
        $subCategoryId = $this->createSubCategory($categoryId);

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

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
        $this->clearMongoDb();

        $subCategoryId = $this->createSubCategory();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

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
     * @param array|null $requestData
     *
     * @dataProvider accessSubCategoryProvider
     */
    public function testAccessSubCategory($url, $method, $role, $responseCode, $requestData = null)
    {
        $this->clearMongoDb();

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
        $accessToken = $this->authAsRole($role);
        if (is_array($requestData)) {
            $requestData = $requestData + array(
                    'name' => 'Тёмное',
                    'category' => $categoryId,
                );
        }

        $response = $this->clientJsonRequest(
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
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                'ROLE_STORE_MANAGER',
                '200',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * POST /api/1/subcategories
             */
            array(
                '/api/1/subcategories',
                'POST',
                'ROLE_COMMERCIAL_MANAGER',
                '201',
                array(),
            ),
            array(
                '/api/1/subcategories',
                'POST',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/subcategories',
                'POST',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/subcategories',
                'POST',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * PUT /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
                array(),
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'PUT',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * DELETE /api/1/subcategories/__SUBCATEGORY_ID__
             */
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                'ROLE_COMMERCIAL_MANAGER',
                '204',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/subcategories/__SUBCATEGORY_ID__',
                'DELETE',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * GET /api/1/categories/__CATEGORY_ID__/subcategories
             */
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                'ROLE_STORE_MANAGER',
                '200',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__/subcategories',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),
        );
    }

    public function testRetailMarkupIsNullOnSubCategoryCreateWithEmptyMarkup()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь', false, 10, 20);
        $categoryId = $this->createCategory($groupId, 'Вино', false);
        $subCategoryId = $this->createSubCategory($categoryId, 'Сухое красное божоле', false);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь', false, 10, 20);
        $categoryId = $this->createCategory($groupId, 'Вино', false);
        $subCategoryId = $this->createSubCategory($categoryId, 'Сухое красное божоле', false);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

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
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь', false, 10, 20);
        $categoryId = $this->createCategory($groupId, 'Вино', false);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'category' => $categoryId,
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
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь', false, 10, 20);
        $categoryId = $this->createCategory($groupId, 'Вино', false);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'category' => $categoryId,
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
}
