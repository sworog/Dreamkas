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

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/klasses',
            $klassData
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

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/klasses',
            $klassData
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

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/klasses/' . $klassId,
            $klassData
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

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/klasses/' . $klassId
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

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/klasses'
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

        $this->createKlass('Прод Тов');

        $postData = array(
            'name' => 'Прод Тов',
        );

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/klasses',
            $postData
        );

        Assert::assertResponseCode(400, $this->client);
        Assert::assertJsonPathEquals('Такой класс уже есть', 'children.name.errors.0', $postResponse);
    }

    public function testDeleteKlassNoCategories()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/klasses/' . $klassId
        );

        Assert::assertResponseCode(200, $this->client);

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/klasses/' . $klassId
        );

        Assert::assertResponseCode(204, $this->client);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/klasses/' . $klassId
        );

        Assert::assertResponseCode(404, $this->client);
    }

    public function testDeleteKlassWithCategories()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();

        $this->createCategory($klassId, '1');
        $this->createCategory($klassId, '2');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/klasses/' . $klassId
        );

        Assert::assertResponseCode(409, $this->client);
    }

    public function testKlassWithCategories()
    {
        $this->clearMongoDb();

        $klassId = $this->createKlass();

        $categoryId1 = $this->createCategory($klassId, '1');
        $categoryId2 = $this->createCategory($klassId, '2');

        $accessToken = $this->authAsRole('ROLE_COMMERCIAL_MANAGER');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/klasses/' . $klassId
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathCount(2, 'categories.*.id', $getResponse);
        Assert::assertJsonPathEquals($categoryId1, 'categories.*.id', $getResponse, 1);
        Assert::assertJsonPathEquals($categoryId2, 'categories.*.id', $getResponse, 1);
    }
}
