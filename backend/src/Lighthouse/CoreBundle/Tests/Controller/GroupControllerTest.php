<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class GroupControllerTest extends WebTestCase
{
    public function testPostGroupsAction()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass('Продовольственные товары');

        $groupData = array(
            'name' => 'Винно-водочные изделия',
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/'. $klassId . '/groups.json',
            array('group' => $groupData)
        );

        Assert::assertResponseCode(201, $this->client);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('Винно-водочные изделия', 'name', $postResponse);
        Assert::assertJsonPathEquals($klassId, 'klass.id', $postResponse);
        Assert::assertJsonPathEquals('Продовольственные товары', 'klass.name', $postResponse);
    }

    public function testUniqueGroupName()
    {
        $this->clearMongoDb();

        $klassId1 = $this->createKlass('Алкоголь');
        $klassId2 = $this->createKlass('Продовольственные товары');

        $groupData = array(
            'name' => 'Винно-водочные изделия',
        );

        // Create first group
        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/'. $klassId1 . '/groups.json',
            array('group' => $groupData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        // Try to create second group with same name in klass 1
        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/'. $klassId1 . '/groups.json',
            array('group' => $groupData)
        );
        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains(
            'Группа с таким названием уже существует в этом классе',
            'children.name.errors',
            $postResponse
        );

        // Create group with same name but in klass 2
        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/'. $klassId2 . '/groups.json',
            array('group' => $groupData)
        );

        Assert::assertResponseCode(201, $this->client);
        Assert::assertJsonHasPath('id', $postResponse);

        // Create second group with same name in klass 2
        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/'. $klassId2 . '/groups.json',
            array('group' => $groupData)
        );
        Assert::assertResponseCode(400, $this->client);

        Assert::assertJsonPathContains(
            'Группа с таким названием уже существует в этом классе',
            'children.name.errors',
            $postResponse
        );
    }

    public function testPostGroupsKlassDoesNotFound()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass('Продовольственные товары');

        $groupData = array(
            'name' => 'Винно-водочные изделия',
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/12312412423/groups.json',
            array('group' => $groupData)
        );

        Assert::assertResponseCode(404, $this->client);
        Assert::assertJsonPathContains('Klass not found', 'message', $postResponse);
        Assert::assertNotJsonHasPath('id', $postResponse);
    }

    /**
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationGroupProvider
     */
    public function testPostGroupsValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass('Продовольственные товары');

        $groupData = $data + array(
            'name' => 'Винно-водочные изделия'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses/' . $klassId . '/groups.json',
            array('group' => $groupData)
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->performJsonAssertions($postResponse, $assertions, true);
    }

    /**
     * @return array
     */
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
     * @param int $expectedCode
     * @param array $data
     * @param array $assertions
     *
     * @dataProvider validationGroupProvider
     */
    public function testPutGroupsValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass('Продовольственные товары');

        $groupId = $this->createGroup($klassId);

        $putData = $data + array(
            'name' => 'Винно-водочные изделия'
        );

        $putResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/klasses/' . $klassId . '/groups/' . $groupId . '.json',
            array('group' => $putData)
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        Assert::assertResponseCode($expectedCode, $this->client);

        $this->performJsonAssertions($putResponse, $assertions, true);
    }

    public function testGetGroup()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();
        $groupId = $this->createGroup($klassId);

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '/groups/' . $groupId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathEquals($groupId, 'id', $getResponse);
        Assert::assertJsonPathEquals($klassId, 'klass.id', $getResponse);
    }

    public function testGetGroupNotFound()
    {
        $this->clearMongoDb();

        $klassId1 = $this->createKlass('1');
        $groupId1 = $this->createGroup($klassId1, '1.1');

        $klassId2 = $this->createKlass('2');
        $groupId2 = $this->createGroup($klassId2, '2.2');

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId1 . '/groups/' . $groupId2 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId2 . '/groups/' . $groupId1 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/invalidId/groups/' . $groupId1 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/invalidId/groups/' . $groupId2 . '.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId1 . '/groups/invalidId.json'
        );

        Assert::assertResponseCode(404, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId2 . '/groups/invalidId.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetGroups()
    {
        $this->clearMongoDb();

        $klassId1 = $this->createKlass('1');
        $klassId2 = $this->createKlass('2');

        $groupId1 = $this->createGroup($klassId1, '1.1');
        $groupId2 = $this->createGroup($klassId1, '1.2');
        $groupId3 = $this->createGroup($klassId1, '1.3');

        $groupId4 = $this->createGroup($klassId2, '2.4');
        $groupId5 = $this->createGroup($klassId2, '2.5');

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId1 . '/groups.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($groupId1, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId2, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId3, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId4, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($groupId5, '*.id', $getResponse, false);


        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId2 . '/groups.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(2, '*.id', $getResponse);
        Assert::assertJsonPathEquals($groupId4, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId5, '*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId1, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($groupId2, '*.id', $getResponse, false);
        Assert::assertJsonPathEquals($groupId3, '*.id', $getResponse, false);
    }

    public function testGetGroupsNotFound()
    {
        $this->clearMongoDb();

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/123484923423/groups.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testGetGroupsEmptyCollection()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();

        $response = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '/groups.json'
        );

        Assert::assertResponseCode(200, $this->client);

        Assert::assertJsonPathCount(0, '*.id', $response);
    }

    public function testDeleteGroup()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();
        $groupId = $this->createGroup($klassId);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '/groups/' . $groupId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/klasses/' . $klassId . '/groups/' . $groupId . '.json'
        );

        Assert::assertResponseCode(204, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '/groups/' . $groupId . '.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }
}
