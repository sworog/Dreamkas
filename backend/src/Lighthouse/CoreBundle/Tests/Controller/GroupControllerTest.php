<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;

class GroupControllerTest extends WebTestCase
{
    public function testPostGroupAction()
    {
        $groupData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1'
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

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

    public function testPostGroupActionWithoutRounding()
    {
        $groupData = array(
            'name' => 'Продовольственные товары',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $groupData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals('Продовольственные товары', 'name', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonHasPath('rounding.name', $postResponse);
    }

    public function testPutGroupActionRoundingUpdated()
    {
        $groupId = $this->createGroup('Алкоголь');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest1', 'rounding.name', $getResponse);

        $groupData = array(
            'name' => 'Алкоголь',
            'rounding' => 'nearest50',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/groups/' . $groupId,
            $groupData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $postResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', 'rounding.name', $getResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('nearest50', '0.rounding.name', $getResponse);
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
        $groupData = $data + array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

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
            'valid rounding no value, should be default rounding' => array(
                201,
                array('rounding' => null),
                array(
                    'rounding.name' => 'nearest1',
                )
            ),
            'valid rounding empty value, should be default rounding' => array(
                201,
                array('rounding' => ''),
                array(
                    'rounding.name' => 'nearest1',
                )
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
        $groupId = $this->createGroup('Прод тов');

        $groupData = $data + array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/groups/' . $groupId,
            $groupData
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        $this->assertResponseCode($expectedCode);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function testGetGroup()
    {
        $groupId = $this->createGroup('Прод Тов');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        Assert::assertJsonPathEquals($groupId, 'id', $postResponse);
        Assert::assertJsonPathEquals('Прод Тов', 'name', $postResponse);
        Assert::assertJsonHasPath('rounding.name', $postResponse);
        Assert::assertJsonHasPath('rounding.title', $postResponse);
    }

    public function testGetGroupWithCategoriesAndSubCategories()
    {
        $groupId = $this->createGroup('1');

        $categoryId1 = $this->createCategory($groupId, '1.1');
        $categoryId2 = $this->createCategory($groupId, '1.2');

        $this->createSubCategory($categoryId1, '1.1.1');
        $this->createSubCategory($categoryId1, '1.1.2');
        $this->createSubCategory($categoryId1, '1.1.3');

        $this->createSubCategory($categoryId2, '1.2.1');
        $this->createSubCategory($categoryId2, '1.2.2');
        $this->createSubCategory($categoryId2, '1.2.3');
        $this->createSubCategory($categoryId2, '1.2.4');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(200);
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
        $groupIds = array();
        for ($i = 0; $i < 5; $i++) {
            $groupIds[$i] = $this->createGroup('Прод Тов' . $i);
        }

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(5, '*.id', $postResponse);

        foreach ($groupIds as $id) {
            Assert::assertJsonPathEquals($id, '*.id', $postResponse);
        }
    }

    public function testGroupUnique()
    {
        $this->createGroup('Прод Тов');

        $postData = array(
            'name' => 'Прод Тов',
        );

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/groups',
            $postData
        );

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Такая группа уже есть', 'children.name.errors.0', $postResponse);
    }

    public function testDeleteGroupNoCategories()
    {
        $groupId = $this->createGroup();

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(200);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(204);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(404);
    }

    public function testDeleteGroupWithCategories()
    {
        $groupId = $this->createGroup();

        $this->createCategory($groupId, '1');
        $this->createCategory($groupId, '2');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(409);
    }

    public function testGroupWithCategories()
    {
        $groupId = $this->createGroup();

        $categoryId1 = $this->createCategory($groupId, '1');
        $categoryId2 = $this->createCategory($groupId, '2');

        $accessToken = $this->factory->oauth()->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/groups/' . $groupId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(2, 'categories.*.id', $getResponse);
        Assert::assertJsonPathEquals($categoryId1, 'categories.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId2, 'categories.*.id', $getResponse, 1);
    }

    /**
     * @param string    $url
     * @param string    $method
     * @param string    $role
     * @param int       $responseCode
     * @param array     $requestData
     *
     * @dataProvider accessGroupProvider
     */
    public function testAccessGroup($url, $method, $role, $responseCode, array $requestData = array())
    {
        $groupId = $this->createGroup();

        $url = str_replace('__GROUP_ID__', $groupId, $url);

        $accessToken = $this->factory->oauth()->authAsRole($role);

        $requestData += array(
            'name' => 'Алкоголь',
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
                200,                              // Response Code
            ),
            array(
                '/api/1/groups',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                403,
            ),
            array(
                '/api/1/groups',
                'GET',
                'ROLE_STORE_MANAGER',
                403,
            ),
            array(
                '/api/1/groups',
                'GET',
                'ROLE_ADMINISTRATOR',
                403,
            ),

            /*************************************
             * GET /api/1/groups/__ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_COMMERCIAL_MANAGER',
                200,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_DEPARTMENT_MANAGER',
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_STORE_MANAGER',
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'GET',
                'ROLE_ADMINISTRATOR',
                403,
            ),

            /*************************************
             * POST /api/1/groups
             */
            array(
                '/api/1/groups',
                'POST',
                'ROLE_COMMERCIAL_MANAGER',
                201,
            ),
            array(
                '/api/1/groups',
                'POST',
                'ROLE_DEPARTMENT_MANAGER',
                403,
            ),
            array(
                '/api/1/groups',
                'POST',
                'ROLE_STORE_MANAGER',
                403,
            ),
            array(
                '/api/1/groups',
                'POST',
                'ROLE_ADMINISTRATOR',
                403,
            ),

            /*************************************
             * PUT /api/1/groups/__GROUP_ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_COMMERCIAL_MANAGER',
                200,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_DEPARTMENT_MANAGER',
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_STORE_MANAGER',
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'PUT',
                'ROLE_ADMINISTRATOR',
                403,
            ),

            /*************************************
             * DELETE /api/1/groups/__GROUP_ID__
             */
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_COMMERCIAL_MANAGER',
                204,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_DEPARTMENT_MANAGER',
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_STORE_MANAGER',
                403,
            ),
            array(
                '/api/1/groups/__GROUP_ID__',
                'DELETE',
                'ROLE_ADMINISTRATOR',
                403,
            ),
        );
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupStoreManagerHasStore($role, $rel)
    {
        $manager = $this->factory->user()->getUser('Василий Петрович Краузе', 'password', $role);

        $groupId = $this->createGroup();
        $storeId = $this->factory->getStore();

        $this->factory->store()->linkManagers($storeId, $manager->id, $rel);

        $accessToken = $this->factory->oauth()->auth($manager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/groups/' . $groupId
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($groupId, 'id', $getResponse);
        Assert::assertJsonHasPath('rounding.name', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupStoreManagerFromAnotherStore($role, $rel)
    {
        $manager = $this->factory->user()->getUser('Василий Петрович Краузе', 'password', $role);

        $groupId = $this->createGroup();
        $storeId1 = $this->factory->getStore('42');
        $storeId2 = $this->factory->getStore('43');

        $this->factory->store()->linkManagers($storeId1, $manager->id, $rel);

        $accessToken = $this->factory->oauth()->auth($manager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/groups/' . $groupId
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupStoreManagerHasNoStore($role)
    {
        $manager = $this->factory->user()->getRoleUser($role);

        $groupId = $this->createGroup();
        $storeId = $this->factory->getStore();

        $accessToken = $this->factory->oauth()->auth($manager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/groups/' . $groupId
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $getResponse);
    }

    /**
     * @param string $role
     * @param string $rel
     * @dataProvider storeRolesProvider
     */
    public function testGetStoreGroupsStoreManagerHasStore($role, $rel)
    {
        $manager = $this->factory->user()->getRoleUser($role);

        $groupId1 = $this->createGroup('1');
        $groupId2 = $this->createGroup('2');
        $groupId3 = $this->createGroup('3');

        $storeId = $this->factory->getStore();

        $this->factory->store()->linkManagers($storeId, $manager->id, $rel);

        $accessToken = $this->factory->oauth()->auth($manager);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/groups'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($groupId1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId3, '*.id', $getResponse, 1);
    }

    /**
     * @return array
     */
    public function storeRolesProvider()
    {
        return array(
            'store manager' => array(User::ROLE_STORE_MANAGER, Store::REL_STORE_MANAGERS),
            'department manager' => array(User::ROLE_DEPARTMENT_MANAGER, Store::REL_DEPARTMENT_MANAGERS),
        );
    }

    public function testUniqueNameInParallel()
    {
        $groupData = array(
            'name' => 'Продовольственные товары',
            'rounding' => 'nearest1'
        );

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/groups', 'POST', $groupData);
        $jsonRequest->setAccessToken($accessToken);

        $responses = $this->client->parallelJsonRequest($jsonRequest, 3);
        $statusCodes = array();
        $jsonResponses = array();
        foreach ($responses as $response) {
            $statusCodes[] = $response->getStatusCode();
            $jsonResponses[] = $this->client->decodeJsonResponse($response);
        }
        $responseBody = \PHPUnit_Util_Type::export($jsonResponses);
        $this->assertCount(1, array_keys($statusCodes, 201), $responseBody);
        $this->assertCount(2, array_keys($statusCodes, 400), $responseBody);
        Assert::assertJsonPathEquals('Продовольственные товары', '*.name', $jsonResponses, 1);
        Assert::assertJsonPathEquals('Такая группа уже есть', '*.children.name.errors.0', $jsonResponses, 2);
    }
}
