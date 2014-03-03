<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;

class FileControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $this->markTestIncomplete();
        $accessToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/files', 'POST');
        $jsonRequest->addHttpHeader('X-File-Name', 'test.txt');
        $jsonRequest->setAccessToken($accessToken);

        $postResponse = $this->client->jsonRequest($jsonRequest);

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('test.txt', 'name', $postResponse);
        Assert::assertJsonPathContains('https', 'url', $postResponse);
        Assert::assertJsonPathContains('selcdn.ru', 'url', $postResponse);
    }
}
