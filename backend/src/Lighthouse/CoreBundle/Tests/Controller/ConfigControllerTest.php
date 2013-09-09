<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ConfigControllerTest extends WebTestCase
{
    public function testPostConfigAction()
    {
        $this->clearMongoDb();

        $configData = array(
            'name' => 'test-config',
            'value' => 'test-config-value',
        );

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            "POST",
            "/api/1/configs",
            $configData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathEquals('test-config', 'name', $postResponse);
        Assert::assertJsonPathEquals('test-config-value', 'value', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);
    }

    public function testPutConfigAction()
    {
        $this->clearMongoDb();

        $configId = $this->createConfig('config', 'test');

        $putData = array(
            'name' => 'config',
            'value' => 'test2',
        );

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/configs/' . $configId,
            $putData
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('config', 'name', $postResponse);
        Assert::assertJsonPathEquals('test2', 'value', $postResponse);
        Assert::assertJsonHasPath('id', $postResponse);
    }

    public function testConfigUnique()
    {
        $this->clearMongoDb();

        $this->createConfig('unique', 'test');

        $postData = array(
            'name' => 'unique',
            'value' => 'test2',
        );

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/configs',
            $postData
        );

        $this->assertResponseCode(400);

        Assert::assertJsonPathContains('Это значение уже используется.', 'children.name.errors.*', $postResponse);
    }

    public function testGetConfigAction()
    {
        $this->clearMongoDb();

        $configId = $this->createConfig("test-config", "test-config-value");

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/configs/' . $configId
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals('test-config', 'name', $response);
        Assert::assertJsonPathEquals('test-config-value', 'value', $response);
        Assert::assertJsonHasPath('id', $response);
    }

    public function testGetConfigsAction()
    {
        $this->clearMongoDb();

        $configId = $this->createConfig("test-config", "test-config-value");
        $configId2 = $this->createConfig("test-config2", "test-config-value2");
        $configId3 = $this->createConfig("test-config3", "test-config-value3");

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/configs'
        );

        Assert::assertJsonPathEquals('test-config', '*.name', $response);
        Assert::assertJsonPathEquals('test-config-value', '*.value', $response);
        Assert::assertJsonPathEquals($configId, '*.id', $response);

        Assert::assertJsonPathEquals('test-config2', '*.name', $response);
        Assert::assertJsonPathEquals('test-config-value2', '*.value', $response);
        Assert::assertJsonPathEquals($configId2, '*.id', $response);

        Assert::assertJsonPathEquals('test-config3', '*.name', $response);
        Assert::assertJsonPathEquals('test-config-value3', '*.value', $response);
        Assert::assertJsonPathEquals($configId3, '*.id', $response);
    }
}
