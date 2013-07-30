<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testPostCategoriesAction()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Продовольственные товары');

        $categoryData = array(
            'name' => 'Винно-водочные изделия',
            'group' => $groupId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('Винно-водочные изделия', 'name', $postResponse);
        Assert::assertJsonPathEquals($groupId, 'group.id', $postResponse);
        Assert::assertJsonPathEquals('Продовольственные товары', 'group.name', $postResponse);
    }

    public function testUniqueCategoryName()
    {
        $this->clearMongoDb();

        $groupId1 = $this->createGroup('Алкоголь');
        $groupId2 = $this->createGroup('Продовольственные товары');

        $categoryData = array(
            'name' => 'Винно-водочные изделия',
            'group' => $groupId1,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        // Create first category
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        // Try to create second category with same name in group 1
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );
        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains(
            'Категория с таким названием уже существует в этой группе',
            'children.name.errors',
            $postResponse
        );

        $categoryData2 = array('group' => $groupId2) + $categoryData;

        // Create category with same name but in group 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData2
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        // Create second category with same name in group 2
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData2
        );
        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains(
            'Категория с таким названием уже существует в этой группе',
            'children.name.errors',
            $postResponse
        );
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationCategoryProvider
     */
    public function testPostCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Продовольственные товары');

        $categoryData = $data + array(
            'name' => 'Винно-водочные изделия',
            'group' => $groupId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $categoryData
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @return array
     */
    public function validationCategoryProvider()
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
            'not valid group' => array(
                400,
                array('group' => '1234'),
                array(
                    'children.group.errors.0'
                    =>
                    'Такой группы не существует'
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
                array('children.retailMarkupMin.errors.*' => 'Значение должно быть числом'),
            ),
            'not valid markup max not float' => array(
                400,
                array('retailMarkupMin' => 10, 'retailMarkupMax' => 'bbb'),
                array('children.retailMarkupMax.errors.*' => 'Значение должно быть числом'),
            ),
        );
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationCategoryProvider
     */
    public function testPutCategoriesValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Продовольственные товары');

        $categoryId = $this->createCategory($groupId);

        $putData = $data + array(
            'name' => 'Винно-водочные изделия',
            'group' => $groupId,
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/categories/' . $categoryId,
            $putData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->performJsonAssertions($putResponse, $assertions, true);
    }

    public function testGetCategory()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathEquals($categoryId, 'id', $getResponse);
        Assert::assertJsonPathEquals($groupId, 'group.id', $getResponse);
    }

    public function testGetCategoryNotFound()
    {
        $this->clearMongoDb();

        $groupId1 = $this->createGroup('1');
        $this->createCategory($groupId1, '1.1');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/invalidId'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetCategoryWithSubcategories()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);

        $this->createSubCategory($categoryId, '1');
        $this->createSubCategory($categoryId, '2');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonHasPath('id', $getResponse);
        Assert::assertJsonHasPath('subCategories', $getResponse);
        Assert::assertJsonPathCount(2, 'subCategories.*.id', $getResponse);
        Assert::assertJsonPathEquals('1', 'subCategories.*.name', $getResponse);
        Assert::assertJsonPathEquals('2', 'subCategories.*.name', $getResponse);
    }

    public function testGetCategories()
    {
        $this->clearMongoDb();

        $groupId1 = $this->createGroup('1');
        $groupId2 = $this->createGroup('2');

        $categoryId1 = $this->createCategory($groupId1, '1.1');
        $categoryId2 = $this->createCategory($groupId1, '1.2');
        $categoryId3 = $this->createCategory($groupId1, '1.3');

        $categoryId4 = $this->createCategory($groupId2, '2.4');
        $categoryId5 = $this->createCategory($groupId2, '2.5');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId1 . '/categories'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($categoryId1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId3, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId4, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($categoryId5, '*.id', $getResponse, false);


        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId2 . '/categories'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($categoryId4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId5, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId1, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($categoryId2, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($categoryId3, '*.id', $getResponse, false);
    }

    public function testGetCategoriesNotFound()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/123484923423/categories'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetCategoriesEmptyCollection()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId . '/categories'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testDeleteCategory()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        Assert::assertResponseCode(200, $this->client);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/categories/' . $categoryId
        );

        Assert::assertResponseCode(204, $this->client);
    }

    public function testDeleteCategoryWithSubcategories()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);

        $this->createSubCategory($categoryId, '1');
        $this->createSubCategory($categoryId, '2');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/categories/' . $categoryId
        );

        Assert::assertResponseCode(409, $this->client);

        $request = new JsonRequest('/api/1/categories/' . $categoryId, 'DELETE');
        $request->setAccessToken($accessToken);
        $request->addHttpHeader('Accept', 'application/json, text/javascript, */*; q=0.01');

        $response = $this->jsonRequest($request);

        Assert::assertResponseCode(409, $this->client);
        Assert::assertJsonHasPath('message', $response);
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array|null $requestData
     *
     * @dataProvider accessCategoryProvider
     */
    public function testAccessCategory($url, $method, $role, $responseCode, $requestData = null)
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();
        $categoryId = $this->createCategory($groupId);

        $url = str_replace(
            array(
                '__CATEGORY_ID__',
                '__GROUP_ID__'
            ),
            array(
                $categoryId,
                $groupId,
            ),
            $url
        );
        $accessToken = $this->authAsRole($role);
        if (is_array($requestData)) {
            $requestData = $requestData + array(
                'name' => 'Пиво',
                'group' => $groupId,
            );
        }

        $response = $this->clientJsonRequest(
            $accessToken,
            $method,
            $url,
            $requestData
        );

        Assert::assertResponseCode($responseCode, $this->client);
    }

    public function accessCategoryProvider()
    {
        return array(
            /*************************************
             * GET /api/1/categories/__ID__
             */
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * POST /api/1/categories
             */
            array(
                '/api/1/categories',
                'POST',
                'ROLE_COMMERCIAL_MANAGER',
                '201',
                array(),
            ),
            array(
                '/api/1/categories',
                'POST',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/categories',
                'POST',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/categories',
                'POST',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * PUT /api/1/categories/__CATEGORY_ID__
             */
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
                array(),
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'PUT',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * DELETE /api/1/categories/__CATEGORY_ID__
             */
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                'ROLE_COMMERCIAL_MANAGER',
                '204',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/categories/__CATEGORY_ID__',
                'DELETE',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * GET /api/1/groups/__GROUP_ID__/categories
             */
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/groups/__GROUP_ID__/categories',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),
        );
    }

    public function testRetailMarkupIsNullOnCategoryCreateWithEmptyMarkup()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь', false, 10, 20);
        $categoryId = $this->createCategory($groupId, 'Вино', false);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $categoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $categoryResponse);
    }

    public function testRetailMarkupIsNotInheritedFromGroupAfterGroupUpdate()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь', false, 10, 20);
        $categoryId = $this->createCategory($groupId, 'Вино', false);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $categoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $categoryResponse);

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

        $categoryResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/categories/' . $categoryId
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $categoryResponse);
        Assert::assertNotJsonHasPath('retailMarkupInherited', $categoryResponse);
    }

    public function testRetailMarkupBecomesNullIfNullMarkupPassed()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Алкоголь', false, 10, 20);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Сухое вино',
            'group' => $groupId,
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $categoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Сладкое вино',
            'group' => $groupId,
            'retailMarkupMin' => null,
            'retailMarkupMax' => null,
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/categories/' . $categoryId,
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

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $postData = array(
            'name' => 'Божоле нуво',
            'group' => $groupId,
            'retailMarkupMin' => 5,
            'retailMarkupMax' => 25,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/categories',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals(5, 'retailMarkupMin', $postResponse);
        Assert::assertJsonPathEquals(25, 'retailMarkupMax', $postResponse);

        $subCategoryId = $postResponse['id'];

        $putData = array(
            'name' => 'Бужуле ново',
            'group' => $groupId,
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/categories/' . $subCategoryId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('retailMarkupMin', $putResponse);
        Assert::assertNotJsonHasPath('retailMarkupMax', $putResponse);
    }
}
