<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
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

    public function testDeleteAction()
    {
        $catalogGroupId = $this->createCatalogGroup('Группа1');

        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $deleteResponse = $this->clientJsonRequest(
            $accessToken,
            'DELETE',
            '/api/1/catalog/groups/' . $catalogGroupId
        );

        $this->assertResponseCode(204);

        $this->assertNull($deleteResponse);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/catalog/groups/' . $catalogGroupId
        );

        $this->assertResponseCode(404);

        $subCategory = $this->getSubCategoryRepository()->find($catalogGroupId);
        $this->assertNull($subCategory);

        $subCategories = $this->getSubCategoryRepository()->findAll();
        $this->assertCount(0, $subCategories);

        $this
            ->getSubCategoryRepository()
            ->getDocumentManager()
            ->getFilterCollection()
            ->disable('softdeleteable');

        $subCategory = $this->getSubCategoryRepository()->find($catalogGroupId);
        $this->assertInstanceOf(SubCategory::getClassName(), $subCategory);

        $subCategories = $this->getSubCategoryRepository()->findAll();
        $this->assertCount(1, $subCategories);
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
