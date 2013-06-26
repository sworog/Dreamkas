<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testAuth()
    {
        $this->clearMongoDb();

        $authClient = $this->createAuthClient();
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

        Assert::assertResponseCode(200, $this->client);
        self::assertNotNull($jsonResponse);

        Assert::assertJsonHasPath('access_token', $jsonResponse);
        Assert::assertJsonHasPath('refresh_token', $jsonResponse);
        Assert::assertJsonHasPath('token_type', $jsonResponse);
        Assert::assertJsonHasPath('expires_in', $jsonResponse);

        Assert::assertJsonPathEquals(86400, 'expires_in', $jsonResponse);
    }

    public function testRefreshToken()
    {
        $this->clearMongoDb();

        $authClient = $this->createAuthClient();
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

        Assert::assertResponseCode(200, $this->client);
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

        Assert::assertResponseCode(200, $this->client);
        self::assertNotNull($jsonResponse);

        Assert::assertJsonHasPath('access_token', $jsonResponse);
        Assert::assertJsonHasPath('refresh_token', $jsonResponse);

        self::assertNotEquals($refreshToken, $jsonResponse['refresh_token']);
        self::assertNotEquals($accessToken, $jsonResponse['access_token']);
    }
}
