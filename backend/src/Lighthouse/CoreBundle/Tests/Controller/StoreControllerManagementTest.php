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
        $storeId = $this->createStore();

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $storeJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, 'managers.*', $storeJson);
        Assert::assertJsonPathEquals($storeUser1->id, 'managers.*.id', $storeJson);
    }

    public function testLinkTwoStoreManagers()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->createUser('storeUser2', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore();

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);
        $request->addLinkHeader($this->getUserResourceUri($storeUser2->id), User::ROLE_STORE_MANAGER);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $storeJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, 'managers.*', $storeJson);
        Assert::assertJsonPathEquals($storeUser1->id, 'managers.*.id', $storeJson);
        Assert::assertJsonPathEquals($storeUser2->id, 'managers.*.id', $storeJson);
    }

    public function testLinkNotStoreManager()
    {
        $this->clearMongoDb();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $depUser1 = $this->createUser('depUser1', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeId = $this->createStore();

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($depUser1->id), User::ROLE_STORE_MANAGER);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('does not have store manager role', 'message', $linkResponse);
    }

    public function testLinkStoreManagerByNotCommercialManager()
    {
        $this->markTestSkipped('Need to move security check before param converter');

        $this->clearMongoDb();

        $storeUser0 = $this->createUser('commUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore();

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
        $storeId = $this->createStore();

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
        $storeId = $this->createStore();

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
        $storeId = $this->createStore();

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
        $storeId = $this->createStore();

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
        $storeId = $this->createStore();

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
        $storeId = $this->createStore();

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
        $storeId = $this->createStore();

        $accessToken = $this->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/notfoundstore' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        Assert::assertJsonPathContains("object not found", 'message', $linkResponse);
    }

    public function testGetStoreManagers()
    {
        $this->clearMongoDb();

        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->createUser('storeUser2', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore();
        $this->linkStoreManagers($storeId, array($storeUser1->id, $storeUser2->id));

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $accessToken = $this->auth($commUser, 'password');

        $managersJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId . '/managers');

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
    }

    public function testGetStoreManagersEmptyList()
    {
        $this->clearMongoDb();

        $storeId = $this->createStore();

        $commUser = $this->createUser('commUser1', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $accessToken = $this->auth($commUser, 'password');

        $managersJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId . '/managers');

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $managersJson);
    }

    public function testGetStoreManagersNotFoundStore()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/notfoundstore/managers');

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('object not found', 'message', $managersJson);
    }

    public function testGetStoreManagersCandidates()
    {
        $this->clearMongoDb();

        $depUser1 = $this->createUser('depUser1', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $depUser2 = $this->createUser('depUser2', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->createUser('storeUser2', 'password', User::ROLE_STORE_MANAGER);
        $storeUser3 = $this->createUser('storeUser3', 'password', User::ROLE_STORE_MANAGER);

        $storeId1 = $this->createStore('42');

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/managers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser3->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($depUser1->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($depUser2->id, '*.id', $managersJson, false);

        $this->linkStoreManagers($storeId1, $storeUser1->id);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/managers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser3->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($depUser1->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($depUser2->id, '*.id', $managersJson, false);

        //
        $storeId2 = $this->createStore('43');

        $this->linkStoreManagers($storeId2, $storeUser3->id);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/managers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser3->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($depUser1->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($depUser2->id, '*.id', $managersJson, false);

        // Check that second store has same candidates
        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/managers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser3->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($depUser1->id, '*.id', $managersJson, false);
        Assert::assertJsonPathEquals($depUser2->id, '*.id', $managersJson, false);

        //
        $this->linkStoreManagers($storeId2, $storeUser2->id);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/managers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*', $managersJson);
    }

    /**
     * @param string $role
     * @dataProvider allRolesExceptCommercialManagerProvider
     */
    public function testGetStoreManagersPermissionForbidden($role)
    {
        $this->clearMongoDb();

        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->createUser('storeUser2', 'password', User::ROLE_STORE_MANAGER);
        $storeUser3 = $this->createUser('storeUser3', 'password', User::ROLE_STORE_MANAGER);

        $storeId1 = $this->createStore();

        $accessToken = $this->authAsRole($role);

        $this->linkStoreManagers($storeId1, $storeUser1->id);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/managers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required roles', 'message', $managersJson);
    }

    /**
     * @return array
     */
    public function allRolesExceptCommercialManagerProvider()
    {
        return array(
            array(User::ROLE_STORE_MANAGER),
            array(User::ROLE_ADMINISTRATOR),
            array(User::ROLE_DEPARTMENT_MANAGER),
        );
    }

    /**
     * @return array
     */
    public function allRolesExceptStoreManagerProvider()
    {
        return array(
            array(User::ROLE_COMMERCIAL_MANAGER),
            array(User::ROLE_ADMINISTRATOR),
            array(User::ROLE_DEPARTMENT_MANAGER),
        );
    }

    public function testUnlinkStoreManager()
    {
        $this->clearMongoDb();
        $storeId1 = $this->createStore();
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->createUser('storeUser2', 'password', User::ROLE_STORE_MANAGER);

        $this->linkStoreManagers($storeId1, $storeUser1->id);

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/managers'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson);

        $jsonRequest = new JsonRequest('/api/1/stores/' . $storeId1, 'UNLINK');
        $jsonRequest->addLinkHeader($this->getUserResourceUri($storeUser1->id), User::ROLE_STORE_MANAGER);
        $this->jsonRequest($jsonRequest, $accessToken);

        $this->assertResponseCode(204);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/managers'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*', $managersJson);
    }

    public function testLinkStoreManagerOptionsCheck()
    {
        $this->clearMongoDb();

        $storeId = $this->createStore();
        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'OPTIONS');
        $request->addHttpHeader('Access-Control-Request-Method', 'Link');
        $request->addHttpHeader('Access-Control-Request-Headers', 'authorization,link');
        $request->addHttpHeader('Origin', 'http://localhost');

        $optionsResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);
    }

    public function testGetUserStore()
    {
        $this->clearMongoDb();
        $storeId1 = $this->createStore();
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);

        $this->linkStoreManagers($storeId1, $storeUser1->id);

        $accessToken = $this->auth($storeUser1);

        $storesResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/' . $storeUser1->id . '/stores'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $storesResponse);
        Assert::assertJsonPathEquals($storeId1, '*.id', $storesResponse);
    }

    public function testGetUserStoreNotFound()
    {
        $this->clearMongoDb();
        $storeId1 = $this->createStore();
        $storeUser1 = $this->createUser('storeUser1', 'password', User::ROLE_STORE_MANAGER);

        $accessToken = $this->auth($storeUser1);

        $storesResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/' . $storeUser1->id . '/stores'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*', $storesResponse);
    }

    /**
     * @param string $role
     * @dataProvider allRolesExceptStoreManagerProvider
     */
    public function testGetUserStoreForbidden($role)
    {
        $this->clearMongoDb();
        $storeId1 = $this->createStore();
        $storeUser1 = $this->createUser('user1', 'password', $role);

        $accessToken = $this->auth($storeUser1);

        $storesResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/' . $storeUser1->id . '/stores'
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required roles', 'message', $storesResponse);
    }

    public function testGetUserStoreByAnotherUserForbidden()
    {
        $this->markTestSkipped('TODO Implement permission check');
        $this->clearMongoDb();

        $storeId1 = $this->createStore('1');
        $storeId2 = $this->createStore('2');
        $storeUser1 = $this->createUser('user1', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->createUser('user2', 'password', User::ROLE_STORE_MANAGER);

        $this->linkStoreManagers($storeId1, $storeUser1->id);
        $this->linkStoreManagers($storeId2, $storeUser2->id);

        $accessToken = $this->auth($storeUser1);

        $storesResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/' . $storeUser2->id . '/stores'
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required roles', 'message', $storesResponse);
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
