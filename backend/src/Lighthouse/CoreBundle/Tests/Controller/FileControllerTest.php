<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Guzzle\Plugin\Mock\MockPlugin;
use Lighthouse\CoreBundle\Document\File\FileUploader;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $accessToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/files', 'POST');
        $jsonRequest->addHttpHeader('X-File-Name', 'test.txt');
        $jsonRequest->setAccessToken($accessToken);

        $mockFile = $this->getFixtureFilePath('OpenStack/auth.response.ok');
        $requestGetContentMock = function (ContainerInterface $container) use ($mockFile) {
            /* @var FileUploader $uploader */
            $uploader = $container->get('lighthouse.core.document.repository.file.uploader');
            $uploader->setFileResource(fopen($mockFile, 'rb'));
        };
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/upload.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/head.response.ok'));

        $mockGuzzle = function (ContainerInterface $container) use ($mockPlugin) {
            $client = $container->get('openstack.selectel');
            $client->addSubscriber($mockPlugin);
        };

        $this->client->addTweaker($requestGetContentMock);
        $this->client->addTweaker($mockGuzzle);

        $postResponse = $this->client->jsonRequest($jsonRequest);

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('test.txt', 'name', $postResponse);
        Assert::assertJsonPathEquals(135, 'size', $postResponse);
        Assert::assertJsonPathContains('https', 'url', $postResponse);
        Assert::assertJsonPathContains('selcdn.ru', 'url', $postResponse);
    }
}
