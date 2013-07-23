<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StoreControllerManagementTest extends WebTestCase
{
    public function testLinkStoreManager()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);
    }

    public function testLinkNotStoreManager()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $depUser1 = $this->createUser('depUser1', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($depUser1->id), User::ROLE_STORE_MANAGER);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('does not have store manager role', 'message', $linkResponse);
    }

    public function testLinkStoreManagerByNotCommercialManager()
    {
        $this->markTestIncomplete('Need to move security check before param converter');

        $this->clearMongoDb();

        $storeUser0 = $this->createUser('commUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($storeUser0, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(403);
    }

    public function testLinkStoreManagerInvalidRel()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_DEPARTMENT_MANAGER);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Invalid rel given', 'message', $linkResponse);
    }

    public function testLinkStoreManagerMissingRel()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addHttpHeader('Link', sprintf('<%s>', $this->getUserResourceUri($storeUser->id)));

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Invalid Link header provided', 'message', $linkResponse);
    }

    public function testLinkStoreManagerMissingLinkHeader()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Link header is required', 'message', $linkResponse);
    }

    public function testLinkStoreManagerNotExistingUser()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri('2143214235345345'), User::ROLE_STORE_MANAGER);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Failed to fetch resource', 'message', $linkResponse);
    }

    public function testLinkStoreManagerInvalidResource()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $groupId = $this->createGroup();
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader('http://localhost/api/1/groups/'.  $groupId, User::ROLE_STORE_MANAGER);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Invalid resource given', 'message', $linkResponse);
    }

    public function testLinkStoreManagerUserAlreadyStoreManager()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(409);

        Assert::assertJsonPathContains("User 'storeUser1' is already store manager", 'message', $linkResponse);
    }

    public function testLinkStoreManagerStoreNotFound()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore('42', '42', '42', false);

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/notfoundstore' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        Assert::assertJsonPathContains("object not found", 'message', $linkResponse);
    }

    /**
     * @param string $userId
     * @return string
     */
    protected function getUserResourceUri($userId)
    {
        return sprintf('http://localhost/api/1/users/%s', $userId);
    }
}
