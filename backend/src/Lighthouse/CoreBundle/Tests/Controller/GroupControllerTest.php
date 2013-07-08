<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

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

        Assert::assertResponseCode(201, $this->client);

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

        Assert::assertResponseCode($expectedCode, $this->client);

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
}
