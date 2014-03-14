<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testAuth()
    {
        $authClient = $this->factory->oauth()->getAuthClient();
        $user = $this->createUser('admin', 'qwerty123');

        $authParams = array(
            'grant_type' => 'password',
            'username' => $user->username,
            'password' => 'qwerty123',
            'client_id' => $authClient->getPublicId(),
            'client_secret' => $authClient->getSecret()
        );

        $this->client->request(
            'POST',
            '/oauth/v2/token',
            $authParams,
            array(),
            array('Content-Type' => 'application/x-www-form-urlencoded')
        );

        $response = $this->client->getResponse()->getContent();
        $jsonResponse = json_decode($response, true);

        $this->assertResponseCode(200);
        self::assertNotNull($jsonResponse);

        Assert::assertJsonHasPath('access_token', $jsonResponse);
        Assert::assertJsonHasPath('refresh_token', $jsonResponse);
        Assert::assertJsonHasPath('token_type', $jsonResponse);
        Assert::assertJsonHasPath('expires_in', $jsonResponse);

        Assert::assertJsonPathEquals(86400, 'expires_in', $jsonResponse);
    }

    public function testRefreshToken()
    {
        $authClient = $this->factory->oauth()->getAuthClient();
        $user = $this->createUser('admin', 'qwerty123');

        $authParams = array(
            'grant_type' => 'password',
            'username' => $user->username,
            'password' => 'qwerty123',
            'client_id' => $authClient->getPublicId(),
            'client_secret' => $authClient->getSecret()
        );

        $this->client->request(
            'POST',
            '/oauth/v2/token',
            $authParams,
            array(),
            array('Content-Type' => 'application/x-www-form-urlencoded')
        );

        $response = $this->client->getResponse()->getContent();
        $jsonResponse = json_decode($response, true);

        $this->assertResponseCode(200);
        self::assertNotNull($jsonResponse);

        Assert::assertJsonHasPath('access_token', $jsonResponse);
        Assert::assertJsonHasPath('refresh_token', $jsonResponse);

        $accessToken = $jsonResponse['access_token'];
        $refreshToken = $jsonResponse['refresh_token'];

        $refreshTokenParams = array(
            'grant_type' => 'refresh_token',
            'client_id' => $authClient->getPublicId(),
            'client_secret' => $authClient->getSecret(),
            'refresh_token' => $refreshToken
        );

        $this->client->request(
            'POST',
            '/oauth/v2/token',
            $refreshTokenParams,
            array(),
            array('Content-Type' => 'application/x-www-form-urlencoded')
        );

        $response = $this->client->getResponse()->getContent();
        $jsonResponse = json_decode($response, true);

        $this->assertResponseCode(200);
        self::assertNotNull($jsonResponse);

        Assert::assertJsonHasPath('access_token', $jsonResponse);
        Assert::assertJsonHasPath('refresh_token', $jsonResponse);

        self::assertNotEquals($refreshToken, $jsonResponse['refresh_token']);
        self::assertNotEquals($accessToken, $jsonResponse['access_token']);
    }

    public function testInvalidPassword()
    {
        $authClient = $this->factory->oauth()->getAuthClient();

        $this->createUser('test', 'password');

        $authParams = array(
            'grant_type' => 'password',
            'username' => 'test',
            'password' => 'invalidPassword',
            'client_id' => $authClient->getPublicId(),
            'client_secret' => $authClient->getSecret()
        );

        $this->client->request(
            'POST',
            '/oauth/v2/token',
            $authParams,
            array(),
            array('Content-Type' => 'application/x-www-form-urlencoded')
        );

        $response = $this->client->getJsonResponse();

        $this->assertResponseCode(400);

        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('invalid_grant', $response['error']);
    }

    public function testOriginOptions()
    {
        $header = array(
            'Content-Type' => 'application/x-www-form-urlencoded',
            'HTTP_Origin' => 'http://webfront.lighthouse.dev',
            'HTTP_Access_Control_Request_Headers' => 'accept, authorization, content-type',
            'HTTP_Access_Control_Request_Method' => 'POST'
        );
        $this->client->request(
            'OPTIONS',
            'http://demo.staging.api.lighthouse.pro/oauth/v2/token',
            array(),
            array(),
            $header
        );

        $this->assertResponseCode(200);
    }
}
