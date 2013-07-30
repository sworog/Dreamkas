<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class GroupControllerTest extends WebTestCase
{
    public function testPostGroupAction()
    {
        $this->clearMongoDb();

        $groupData = array(
            'name' => 'Продовольственные товары'
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $groupData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals('Продовольственные товары', 'name', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);
    }

    /**
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationGroupProvider
     */
    public function testPostGroupValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $groupData = $data + array(
            'name' => 'Продовольственные товары'
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $groupData
        );

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function validationGroupProvider()
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
     * @param $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationGroupProvider
     */
    public function testPutGroupValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Прод тов');

        $groupData = $data + array(
            'name' => 'Продовольственные товары'
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/groups/' . $groupId,
            $groupData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function testGetGroup()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('Прод Тов');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        Assert::assertJsonPathEquals($groupId, 'id', $postResponse);
        Assert::assertJsonPathEquals('Прод Тов', 'name', $postResponse);
    }

    public function testGetGroupWithCategoriesAndSubCategories()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup('1');

        $categoryId1 = $this->createCategory($groupId, '1.1');
        $categoryId2 = $this->createCategory($groupId, '1.2');

        $subCategory1 = $this->createSubCategory($categoryId1, '1.1.1');
        $subCategory2 = $this->createSubCategory($categoryId1, '1.1.2');
        $subCategory3 = $this->createSubCategory($categoryId1, '1.1.3');

        $subCategory4 = $this->createSubCategory($categoryId2, '1.2.1');
        $subCategory5 = $this->createSubCategory($categoryId2, '1.2.2');
        $subCategory6 = $this->createSubCategory($categoryId2, '1.2.3');
        $subCategory7 = $this->createSubCategory($categoryId2, '1.2.4');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonHasPath('id', $getResponse);
        Assert::assertJsonHasPath('categories', $getResponse);

        Assert::assertJsonPathCount(2, 'categories.*.id', $getResponse);
        Assert::assertJsonPathEquals($categoryId1, 'categories.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId2, 'categories.*.id', $getResponse, 1);

        Assert::assertJsonPathCount(true, 'categories.0.subCategories.*.id', $getResponse);
        Assert::assertJsonPathCount(true, 'categories.1.subCategories.*.id', $getResponse);
    }

    public function testGetGroups()
    {
        $this->clearMongoDb();

        $groupIds = array();
        for ($i = 0; $i < 5; $i++) {
            $groupIds[$i] = $this->createGroup('Прод Тов' . $i);
        }

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups'
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathCount(5, '*.id', $postResponse);

        foreach ($groupIds as $id) {
            Assert::assertJsonPathEquals($id, '*.id', $postResponse);
        }
    }

    public function testGroupUnique()
    {
        $this->clearMongoDb();

        $this->createGroup('Прод Тов');

        $postData = array(
            'name' => 'Прод Тов',
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $postData
        );

        Assert::assertResponseCode(400, $this->client);
        Assert::assertJsonPathEquals('Такая группа уже есть', 'children.name.errors.0', $postResponse);
    }

    public function testDeleteGroupNoCategories()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        Assert::assertResponseCode(200, $this->client);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/groups/' . $groupId
        );

        Assert::assertResponseCode(204, $this->client);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testDeleteGroupWithCategories()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();

        $this->createCategory($groupId, '1');
        $this->createCategory($groupId, '2');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/groups/' . $groupId
        );

        Assert::assertResponseCode(409, $this->client);
    }

    public function testGroupWithCategories()
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();

        $categoryId1 = $this->createCategory($groupId, '1');
        $categoryId2 = $this->createCategory($groupId, '2');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathCount(2, 'categories.*.id', $getResponse);
        Assert::assertJsonPathEquals($categoryId1, 'categories.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId2, 'categories.*.id', $getResponse, 1);
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array|null $requestData
     *
     * @dataProvider accessGroupProvider
     */
    public function testAccessGroup($url, $method, $role, $responseCode, $requestData = null)
    {
        $this->clearMongoDb();

        $groupId = $this->createGroup();

        $url = str_replace(
            array(
                '__GROUP_ID__',
            ),
            array(
                $groupId,
            ),
            $url
        );
        $accessToken = $this->authAsRole($role);
        if (is_array($requestData)) {
            $requestData = $requestData + array(
                'name' => 'Алкоголь'
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

    public function accessGroupProvider()
    {
        return array(
            /**************************************
             * GET /api/1/groups
             */
            array(
                '/api/1/groups',
                'GET',                              // Method
                'ROLE_COMMERCIAL_MANAGER',          // Role
                '200',                              // Response Code
            ),
            array(
                '/api/1/groups',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/groups',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/groups',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * GET /api/1/groups/__ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                '200',
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_ADMINISTRATOR',
                '403',
            ),

            /*************************************
             * POST /api/1/groups
             */
            array(
                '/api/1/groups',
                'POST',
                'ROLE_COMMERCIAL_MANAGER',
                '201',
                array(),
            ),
            array(
                '/api/1/groups',
                'POST',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/groups',
                'POST',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/groups',
                'POST',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * PUT /api/1/groups/__GROUP_ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_COMMERCIAL_MANAGER',
                '200',
                array(),
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_STORE_MANAGER',
                '403',
                array(),
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_ADMINISTRATOR',
                '403',
                array(),
            ),

            /*************************************
             * DELETE /api/1/groups/__GROUP_ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_COMMERCIAL_MANAGER',
                '204',
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_DEPARTMENT_MANAGER',
                '403',
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_STORE_MANAGER',
                '403',
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_ADMINISTRATOR',
                '403',
            ),
        );
    }
}
