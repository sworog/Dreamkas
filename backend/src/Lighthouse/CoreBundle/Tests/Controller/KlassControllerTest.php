<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class KlassControllerTest extends WebTestCase
{
    public function testPostKlassAction()
    {
        $this->clearMongoDb();

        $klassData = array(
            'name' => 'Продовольственные товары'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses.json',
            array('klass' => $klassData)
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
     * @dataProvider validationKlassProvider
     */
    public function testPostKlassValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $klassData = $data + array(
            'name' => 'Продовольственные товары'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses.json',
            array('klass' => $klassData)
        );

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function validationKlassProvider()
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
     * @dataProvider validationKlassProvider
     */
    public function testPutKlassValidation($expectedCode, array $data, array $assertions = array())
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass('Прод тов');

        $klassData = $data + array(
            'name' => 'Продовольственные товары'
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'PUT',
            '/api/1/klasses/' . $klassId .'.json',
            array('klass' => $klassData)
        );

        $expectedCode = ($expectedCode == 201) ? 200 : $expectedCode;

        Assert::assertResponseCode($expectedCode, $this->client);

        foreach ($assertions as $path => $expected) {
            Assert::assertJsonPathContains($expected, $path, $postResponse);
        }
    }

    public function testGetKlass()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass('Прод Тов');

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '.json'
        );

        Assert::assertJsonPathEquals($klassId, 'id', $postResponse);
        Assert::assertJsonPathEquals('Прод Тов', 'name', $postResponse);
    }

    public function testGetKlasses()
    {
        $this->clearMongoDb();

        $klassIds = array();
        for ($i = 0; $i < 5; $i++) {
            $klassIds[$i] = $this->createKlass('Прод Тов' . $i);
        }

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses.json'
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathCount(5, '*.id', $postResponse);

        foreach ($klassIds as $id) {
            Assert::assertJsonPathEquals($id, '*.id', $postResponse);
        }
    }

    public function testKlassUnique()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass('Прод Тов');

        $postData = array(
            'name' => 'Прод Тов',
        );

        $postResponse = $this->clientJsonRequest(
            $this->client,
            'POST',
            '/api/1/klasses.json',
            array('klass' => $postData)
        );

        Assert::assertResponseCode(400, $this->client);
        Assert::assertJsonPathEquals('Такой класс уже есть', 'children.name.errors.0', $postResponse);
    }

    public function testDeleteKlassNoGroups()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/klasses/' . $klassId . '.json'
        );

        Assert::assertResponseCode(204, $this->client);

        $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '.json'
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testDeleteKlassWithGroups()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();

        $this->createGroup($klassId, '1');
        $this->createGroup($klassId, '2');

        $this->clientJsonRequest(
            $this->client,
            'DELETE',
            '/api/1/klasses/' . $klassId . '.json'
        );

        Assert::assertResponseCode(409, $this->client);
    }

    public function testKlassWithGroups()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();

        $groupId1 = $this->createGroup($klassId, '1');
        $groupId2 = $this->createGroup($klassId, '2');

        $getResponse = $this->clientJsonRequest(
            $this->client,
            'GET',
            '/api/1/klasses/' . $klassId . '.json'
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathCount(2, 'groups.*.id', $getResponse);
        Assert::assertJsonPathEquals($groupId1, 'groups.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($groupId2, 'groups.*.id', $getResponse, 1);
    }
}
