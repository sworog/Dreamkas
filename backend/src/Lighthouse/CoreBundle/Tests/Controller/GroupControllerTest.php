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

        Assert::assertJsonPathContains('Группа с таким названием уже существует в этом классе', 'children.name.errors', $postResponse);

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

        Assert::assertJsonPathContains('Группа с таким названием уже существует в этом классе', 'children.name.errors', $postResponse);
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
        Assert::assertJsonPathContains('Klass not found', '*.message', $postResponse);
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
}
