<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
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
}
