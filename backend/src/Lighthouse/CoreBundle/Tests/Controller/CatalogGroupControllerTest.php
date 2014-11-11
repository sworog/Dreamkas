<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryRepository;
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
        Assert::assertJsonPathEquals('nearest1', 'rounding.name', $postResponse);
        Assert::assertNotJsonHasPath('category', $postResponse);

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
            'errors.children.name.errors.0',
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
        Assert::assertNotJsonHasPath('*.category', $getResponse);
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

    public function testDeleteAction()
    {
        $groupName = 'Группа1';
        $catalogGroupId = $this->createCatalogGroup($groupName);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/catalog/groups/' . $catalogGroupId
        );

        $this->assertResponseCode(204);

        $this->assertNull($deleteResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/catalog/groups/' . $catalogGroupId
        );

        $this->assertResponseCode(200);
        $this->assertNotEquals($groupName, $getResponse['name']);
    }

    public function testDeleteCatalogGroupIsNotVisibleInList()
    {
        $catalogGroupId1 = $this->createCatalogGroup('Группа1');
        $catalogGroupId2 = $this->createCatalogGroup('Группа2');

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/catalog/groups/' . $catalogGroupId1
        );

        $this->assertResponseCode(204);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/catalog/groups'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals($catalogGroupId2, '*.id', $getResponse);
        Assert::assertNotJsonPathEquals($catalogGroupId1, '*.id', $getResponse);
    }

    public function testDeleteWithDuplicateName()
    {
        $catalogGroupId1 = $this->createCatalogGroup('Хомячки');

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/catalog/groups/' . $catalogGroupId1
        );

        $this->assertResponseCode(204);

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/catalog/groups',
            array('name' => 'Хомячки')
        );

        $this->assertResponseCode(201);
        $this->assertNotEquals($catalogGroupId1, $postResponse['id']);
    }

    public function testDeleteGroupAfterAllProductsDelete()
    {
        $catalogGroup = $this->factory()->catalog()->getSubCategory('To be deleted');

        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'), $catalogGroup);

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        // try to delete group with 3 products
        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/catalog/groups/{$catalogGroup->id}"
        );
        $this->assertResponseCode(409);

        // delete 1st product and try to delete group with 2 products, should fail
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/products/{$products['1']->id}"
        );
        $this->assertResponseCode(204);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/catalog/groups/{$catalogGroup->id}"
        );
        $this->assertResponseCode(409);

        // delete 2nd product and try to delete group with 1 product, should fail
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/products/{$products['2']->id}"
        );
        $this->assertResponseCode(204);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/catalog/groups/{$catalogGroup->id}"
        );
        $this->assertResponseCode(409);

        // delete 3rd product and try to delete group without products, should pass
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/products/{$products['3']->id}"
        );
        $this->assertResponseCode(204);

        $this->client->setCatchException();
        $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            "/api/1/catalog/groups/{$catalogGroup->id}"
        );
        $this->assertResponseCode(204);
    }

    /**
     * @return SubCategoryRepository
     */
    protected function getSubCategoryRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.classifier.subcategory');
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
