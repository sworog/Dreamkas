<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\Factory\UserFactory;
use Lighthouse\CoreBundle\Test\WebTestCase;

class StoreControllerManagementTest extends WebTestCase
{
    /**
     * @param string $userId
     * @return string
     */
    protected function getUserResourceUri($userId)
    {
        return sprintf('http://localhost/api/1/users/%s', $userId);
    }

    public function testLinkStoreManager()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_STORE_MANAGERS);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $storeJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, 'storeManagers.*', $storeJson);
        Assert::assertJsonPathEquals($storeUser1->id, 'storeManagers.*.id', $storeJson);
    }

    public function testLinkStoreManagerLinkMethodByQueryParam()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'POST');
        $request->parameters['_method'] = 'LINK';
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_STORE_MANAGERS);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $storeJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, 'storeManagers.*', $storeJson);
        Assert::assertJsonPathEquals($storeUser1->id, 'storeManagers.*.id', $storeJson);
    }

    public function testLinkTwoStoreManagers()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->factory()->user()->getUser('storeUser2@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_STORE_MANAGERS);
        $request->addLinkHeader($this->getUserResourceUri($storeUser2->id), Store::REL_STORE_MANAGERS);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $storeJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, 'storeManagers.*', $storeJson);
        Assert::assertJsonPathEquals($storeUser1->id, 'storeManagers.*.id', $storeJson);
        Assert::assertJsonPathEquals($storeUser2->id, 'storeManagers.*.id', $storeJson);
    }

    public function testLinkNotStoreManager()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $depUser1 = $this->factory()->user()->getUser('depUser1@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($depUser1->id), Store::REL_STORE_MANAGERS);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('does not have ROLE_STORE_MANAGER role', 'message', $linkResponse);
    }

    public function testLinkStoreManagerInvalidRel()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), 'invalid');

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Invalid rel given', 'message', $linkResponse);
    }

    public function testLinkStoreManagerMissingRel()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addHttpHeader('Link', sprintf('<%s>', $this->getUserResourceUri($storeUser->id)));

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Invalid Link header provided', 'message', $linkResponse);
    }

    public function testLinkStoreManagerMissingLinkHeader()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Link header is required', 'message', $linkResponse);
    }

    public function testLinkStoreManagerNotExistingUser()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri('2143214235345345'), Store::REL_STORE_MANAGERS);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Failed to fetch resource', 'message', $linkResponse);
    }

    public function testLinkStoreManagerInvalidResource()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $groupId = $this->createGroup();
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader('http://localhost/api/1/groups/' . $groupId, Store::REL_STORE_MANAGERS);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Invalid resource given', 'message', $linkResponse);
    }

    public function testLinkStoreManagerUserAlreadyStoreManager()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_STORE_MANAGERS);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(409);

        Assert::assertJsonPathContains("User 'storeUser1@lh.pro' is already store manager", 'message', $linkResponse);
    }

    public function testLinkStoreManagerStoreNotFound()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/notfoundstore' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_STORE_MANAGERS);

        $linkResponse = $this->jsonRequest($request, $accessToken);

        Assert::assertJsonPathContains("object not found", 'message', $linkResponse);
    }

    public function testGetStoreManagers()
    {
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->factory()->user()->getUser('storeUser2@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();
        $this->factory()->store()->linkStoreManagers(array($storeUser1->id, $storeUser2->id), $storeId);

        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $managersJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId . '/storeManagers');

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
    }

    public function testGetStoreManagersEmptyList()
    {
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId . '/storeManagers');

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $managersJson);
    }

    public function testGetStoreManagersNotFoundStore()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/notfoundstore/storeManagers');

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('object not found', 'message', $managersJson);
    }

    public function testGetStoreManagersCandidates()
    {
        $depUser1 = $this->factory()->user()->getUser('depUser1@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $depUser2 = $this->factory()->user()->getUser('depUser2@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->factory()->user()->getUser('storeUser2@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeUser3 = $this->factory()->user()->getUser('storeUser3@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $storeId1 = $this->factory()->store()->getStoreId('42');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser3->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($depUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser2->id, '*.id', $managersJson);

        $this->factory()->store()->linkStoreManagers($storeUser1->id, $storeId1);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($storeUser1->id, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($storeUser3->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($depUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser2->id, '*.id', $managersJson);

        //
        $storeId2 = $this->factory()->store()->getStoreId('43');

        $this->factory()->store()->linkStoreManagers($storeUser3->id, $storeId2);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($storeUser1->id, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($storeUser3->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser2->id, '*.id', $managersJson);

        // Check that second store has same candidates
        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/storeManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($storeUser1->id, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser2->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($storeUser3->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser2->id, '*.id', $managersJson);

        //
        $this->factory()->store()->linkStoreManagers($storeUser2->id, $storeId2);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers',
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
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $this->factory()->user()->getUser('storeUser2@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $this->factory()->user()->getUser('storeUser3@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $storeId1 = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->authAsRole($role);

        $this->factory()->store()->linkStoreManagers($storeUser1->id, $storeId1);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers',
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
            array(Store::REL_STORE_MANAGERS),
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
        );
    }

    public function testUnlinkStoreManager()
    {
        $storeId1 = $this->factory()->store()->getStoreId();
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $this->factory()->user()->getUser('storeUser2@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $this->factory()->store()->linkStoreManagers($storeUser1->id, $storeId1);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertJsonPathEquals($storeUser1->id, '*.id', $managersJson);

        $jsonRequest = new JsonRequest('/api/1/stores/' . $storeId1, 'UNLINK');
        $jsonRequest->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_STORE_MANAGERS);
        $this->jsonRequest($jsonRequest, $accessToken);

        $this->assertResponseCode(204);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*', $managersJson);
    }

    public function testUnlinkStoreManagerNotFromStore()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $storeUser = $this->factory()->user()->getUser('storeUser@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/stores/' . $storeId, 'UNLINK');
        $jsonRequest->addLinkHeader($this->getUserResourceUri($storeUser->id), Store::REL_STORE_MANAGERS);
        $unlinkResponse = $this->jsonRequest($jsonRequest, $accessToken);

        $this->assertResponseCode(409);

        Assert::assertJsonPathContains('is not store manager', 'message', $unlinkResponse);
    }

    public function testLinkStoreManagerOptionsCheck()
    {
        $storeId = $this->factory()->store()->getStoreId();
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_STORE_MANAGER);

        $request = new JsonRequest('/api/1/stores/' . $storeId . '/storeManagers?candidates=1', 'OPTIONS');
        $request->addHttpHeader('Access-Control-Request-Method', 'LINK');
        $request->addHttpHeader('Access-Control-Request-Headers', 'Authorization,Link');
        $request->addHttpHeader('Origin', 'http://webfront.localhost');

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(200);

        $response = $this->client->getResponse();

        $this->assertContains('LINK', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertContains('UNLINK', $response->headers->get('Access-Control-Allow-Methods'));

        $this->assertContains('authorization', $response->headers->get('Access-Control-Allow-Headers'));
        $this->assertContains('link', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testGetUserStore()
    {
        $storeId1 = $this->factory()->store()->getStoreId();
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $this->factory()->store()->linkStoreManagers($storeUser1->id, $storeId1);

        $accessToken = $this->factory()->oauth()->auth($storeUser1);

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
        $this->factory()->store()->getStoreId();
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $accessToken = $this->factory()->oauth()->auth($storeUser1);

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
        $this->factory()->store()->getStoreId();
        $storeUser1 = $this->factory()->user()->getUser('user1@lh.pro', 'password', $role);

        $accessToken = $this->factory()->oauth()->auth($storeUser1);

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
        $storeId1 = $this->factory()->store()->getStoreId('1');
        $storeId2 = $this->factory()->store()->getStoreId('2');
        $storeUser1 = $this->factory()->user()->getUser('user1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->factory()->user()->getUser('user2@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $this->factory()->store()->linkStoreManagers($storeUser1->id, $storeId1);
        $this->factory()->store()->linkStoreManagers($storeUser2->id, $storeId2);

        $accessToken = $this->factory()->oauth()->auth($storeUser1);

        $storesResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/users/' . $storeUser2->id . '/stores'
        );

        $this->assertResponseCode(403);

        Assert::assertJsonPathContains('Token does not have the required permissions', 'message', $storesResponse);
    }

    public function testLinkDepartmentManager()
    {
        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('depUser1@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'LINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_DEPARTMENT_MANAGERS);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $storeJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, 'departmentManagers.*', $storeJson);
        Assert::assertJsonPathEquals($storeUser1->id, 'departmentManagers.*.id', $storeJson);
    }

    public function testUnlinkDepartmentManager()
    {
        $storeUser1 = $this->factory()->user()->getUser('depUser1@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();

        $this->factory()->store()->linkDepartmentManagers($storeUser1->id, $storeId);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $request = new JsonRequest('/api/1/stores/' . $storeId, 'UNLINK');
        $request->addLinkHeader($this->getUserResourceUri($storeUser1->id), Store::REL_DEPARTMENT_MANAGERS);

        $this->jsonRequest($request, $accessToken);

        $this->assertResponseCode(204);

        $storeJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/' . $storeId);

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, 'departmentManagers.*', $storeJson);
    }

    public function testGetStoreDepartmentManagers()
    {
        $depUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $depUser2 = $this->factory()->user()->getUser('storeUser2@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeId = $this->factory()->store()->getStoreId();
        $this->factory()->store()->linkDepartmentManagers(array($depUser1->id, $depUser2->id), $storeId);

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/departmentManagers'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $managersJson);
        Assert::assertJsonPathEquals($depUser1->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($depUser2->id, '*.id', $managersJson, 1);
    }

    public function testGetDepartmentManagersEmptyList()
    {
        $storeId = $this->factory()->store()->getStoreId();

        $commUser = $this->factory()->user()->getUser('commUser1@lh.pro', 'password', User::ROLE_COMMERCIAL_MANAGER);
        $accessToken = $this->factory()->oauth()->auth($commUser, 'password');

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/departmentManagers'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $managersJson);
    }

    public function testGetDepartmentManagersNotFoundStore()
    {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest($accessToken, 'GET', '/api/1/stores/notFoundStore/departmentManagers');

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('object not found', 'message', $managersJson);
    }

    public function testGetDepartmentManagersCandidates()
    {
        $depUser1 = $this->factory()->user()->getUser('depUser1@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $depUser2 = $this->factory()->user()->getUser('depUser2@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $depUser3 = $this->factory()->user()->getUser('depUser3@lh.pro', 'password', User::ROLE_DEPARTMENT_MANAGER);
        $storeUser1 = $this->factory()->user()->getUser('storeUser1@lh.pro', 'password', User::ROLE_STORE_MANAGER);
        $storeUser2 = $this->factory()->user()->getUser('storeUser2@lh.pro', 'password', User::ROLE_STORE_MANAGER);

        $storeId1 = $this->factory()->store()->getStoreId('42');

        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/departmentManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(3, '*.id', $managersJson);
        Assert::assertJsonPathEquals($depUser1->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($depUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($depUser3->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($storeUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($storeUser2->id, '*.id', $managersJson);

        $this->factory()->store()->linkDepartmentManagers($depUser1->id, $storeId1);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/departmentManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser1->id, '*.id', $managersJson);
        Assert::assertJsonPathEquals($depUser2->id, '*.id', $managersJson, 1);
        Assert::assertJsonPathEquals($depUser3->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($storeUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($storeUser2->id, '*.id', $managersJson);

        //
        $storeId2 = $this->factory()->store()->getStoreId('43');

        $this->factory()->store()->linkDepartmentManagers($depUser2->id, $storeId2);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/departmentManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser2->id, '*.id', $managersJson);
        Assert::assertJsonPathEquals($depUser3->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($storeUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($storeUser2->id, '*.id', $managersJson);

        // Check that second store has same candidates
        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId2 . '/departmentManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($depUser2->id, '*.id', $managersJson);
        Assert::assertJsonPathEquals($depUser3->id, '*.id', $managersJson, 1);
        Assert::assertNotJsonPathEquals($storeUser1->id, '*.id', $managersJson);
        Assert::assertNotJsonPathEquals($storeUser2->id, '*.id', $managersJson);

        //
        $this->factory()->store()->linkDepartmentManagers($depUser3->id, $storeId2);

        $managersJson = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId1 . '/storeManagers',
            null,
            array('candidates' => 1)
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*', $managersJson);
    }

    public function testStoreCreatorIsAddedToManagers()
    {
        $master = $this->factory()->user()->createUser(
            'master@lighthouse.pro',
            UserFactory::USER_DEFAULT_PASSWORD,
            array(
                User::ROLE_COMMERCIAL_MANAGER,
                User::ROLE_DEPARTMENT_MANAGER,
                User::ROLE_STORE_MANAGER
            ),
            'Master'
        );

        $accessToken = $this->factory()->oauth()->auth($master);

        $storeData = array(
            'number' => '1',
            'address' => 'HellRoad 666',
            'contacts' => '112'
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores',
            $storeData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals($master->id, 'storeManagers.*.id', $response);
        Assert::assertJsonPathEquals($master->id, 'departmentManagers.*.id', $response);
    }
}
