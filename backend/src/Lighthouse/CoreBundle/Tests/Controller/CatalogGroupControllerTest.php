<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class CatalogGroupControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $postData = array(
            'name' => 'Скобянные изделия',
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/catalog/groups',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals('Скобянные изделия', 'name', $postResponse);
        Assert::assertJsonPathEquals('default', 'category.name', $postResponse);
        Assert::assertJsonPathEquals('default', 'category.group.name', $postResponse);
        Assert::assertJsonPathEquals('nearest1', 'rounding.name', $postResponse);

        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/catalog/groups/' . $id
        );

        $this->assertResponseCode(200);

        $this->assertEquals($postResponse, $getResponse);
    }

    public function testDoubleGroupName()
    {
        $this->createCatalogGroup('Хомячки');

        $postData = array(
            'name' => 'Хомячки',
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/catalog/groups',
            $postData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathEquals(
            'Группа с таким названием уже существует',
            'children.name.errors.0',
            $postResponse
        );
    }

    public function testGetAction()
    {
        $catalogGroupId1 = $this->createCatalogGroup('Группа1');
        $catalogGroupId2 = $this->createCatalogGroup('Группа2');
        $catalogGroupId3 = $this->createCatalogGroup('Группа3');

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/catalog/groups'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalogGroupId1, '0.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalogGroupId2, '1.id', $getResponse, 1);
        Assert::assertJsonPathEquals($catalogGroupId3, '2.id', $getResponse, 1);
    }

    public function testPutAction()
    {
        $catalogGroupId = $this->createCatalogGroup('Группа1');

        $putData = array(
            'name' => 'Хомячки',
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/catalog/groups/' . $catalogGroupId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('Хомячки', 'name', $putResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/catalog/groups/' . $catalogGroupId
        );

        $this->assertResponseCode(200);

        $this->assertEquals($putResponse, $getResponse);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function createCatalogGroup($name)
    {
        $postData = array(
            'name' => $name,
        );

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/catalog/groups',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);

        return $postResponse['id'];
    }
}
