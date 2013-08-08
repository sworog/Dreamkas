<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Document\User\User;

class StoreProductControllerTest extends WebTestCase
{
    public function testGetActionNoStoreProductCreated()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();
        $storeId = $this->createStore();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $productId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($productId, 'product.id', $getResponse);
        Assert::assertJsonPathEquals($storeId, 'store.id', $getResponse);
        Assert::assertNotJsonHasPath('id', $getResponse);
    }

    public function testGetActionProductDoesNotExist()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();
        $storeId = $this->createStore();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/aaaa'
        );

        $this->assertResponseCode(404);
        Assert::assertJsonPathContains('No route found', 'message', $getResponse, false);
    }

    public function testGetActionProductExistsStoreNotExists()
    {
        $this->clearMongoDb();

        $productId = $this->createProduct();
        $storeId = $this->createStore();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/aaa/products/' . $productId
        );

        $this->assertResponseCode(404);
        Assert::assertJsonPathContains('No route found', 'message', $getResponse, false);
    }
}
