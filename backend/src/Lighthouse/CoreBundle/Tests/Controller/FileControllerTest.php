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
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/files', 'POST');
        $jsonRequest->addHttpHeader('X-File-Name', 'test.txt');
        $jsonRequest->setAccessToken($accessToken);

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/upload.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/head.response.ok'));

        $mockGuzzle = function (ContainerInterface $container) use ($mockPlugin) {
            $client = $container->get('openstack.selectel');
            $client->addSubscriber($mockPlugin);
        };

        $mockFile = $this->getFixtureFilePath('OpenStack/auth.response.ok');
        $requestGetContentMock = function (ContainerInterface $container) use ($mockFile) {
            /* @var FileUploader $uploader */
            $uploader = $container->get('lighthouse.core.document.repository.file.uploader');
            $uploader->setFileResource(fopen($mockFile, 'rb'));
        };

        $this->client->addTweaker($mockGuzzle);
        $this->client->addTweaker($requestGetContentMock);

        $postResponse = $this->client->jsonRequest($jsonRequest);

        $this->assertResponseCode(201);

        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals('test.txt', 'name', $postResponse);
        Assert::assertJsonPathEquals(135, 'size', $postResponse);
        Assert::assertJsonPathContains('https', 'url', $postResponse);
        Assert::assertJsonPathContains('selcdn.ru', 'url', $postResponse);
    }

    /**
     * @dataProvider providerPostValidation
     * @param array $headers
     * @param int $expectedCode
     * @param array $assertions
     * @param int $expectedRequestsCount
     */
    public function testPostActionValidation(
        array $headers,
        $expectedCode,
        array $assertions,
        $expectedRequestsCount
    ) {
        $accessToken = $this->factory()->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/files', 'POST');
        $mockFile = $this->getFixtureFilePath('OpenStack/auth.response.ok');

        $headers += array(
            'X-File-Name' => 'test.txt',
            'Content-Length' => filesize($mockFile),
        );
        foreach ($headers as $name => $value) {
            $jsonRequest->addHttpHeader($name, $value);
        }
        $jsonRequest->setAccessToken($accessToken);

        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/auth.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/container.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/upload.response.ok'));
        $mockPlugin->addResponse($this->getFixtureFilePath('OpenStack/head.response.ok'));

        $mockGuzzle = function (ContainerInterface $container) use ($mockPlugin) {
            $client = $container->get('openstack.selectel');
            $client->addSubscriber($mockPlugin);
        };

        $requestGetContentMock = function (ContainerInterface $container) use ($mockFile) {
            /* @var FileUploader $uploader */
            $uploader = $container->get('lighthouse.core.document.repository.file.uploader');
            $uploader->setFileResource(fopen($mockFile, 'rb'));
        };

        $this->client->addTweaker($mockGuzzle);
        $this->client->addTweaker($requestGetContentMock);

        $postResponse = $this->client->jsonRequest($jsonRequest);
        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);

        $this->assertCount($expectedRequestsCount, $mockPlugin->getReceivedRequests());
    }

    /**
     * @return array
     */
    public function providerPostValidation()
    {
        return array(
            'Content-Length 10Mb + 1' => array(
                array(
                    'Content-Length' => 10485761,
                ),
                400,
                array(
                    'errors.0' => 'Размер файла должен быть меньше 10Мб',
                    'errors.1' => null,
                ),
                2
            ),
            'Content-Length 10Mb' => array(
                array(
                    'Content-Length' => 10485760,
                ),
                201,
                array(),
                4
            ),
            'Content-Length 1Kb' => array(
                array(
                    'Content-Length' => 1024,
                ),
                201,
                array(),
                4
            ),
            'Missing X-File-Name' => array(
                array(
                    'X-File-Name' => null,
                ),
                400,
                array(
                    'errors.0' => 'Отсутствует заголовок X-File-Name',
                ),
                2
            ),
            'X-File-Name with UTF-8' => array(
                array(
                    'X-File-Name' => 'Договор с ООО "ЕВРОАРТ" от 2014.02.03.docx',
                ),
                201,
                array(
                    'name' => 'Договор с ООО "ЕВРОАРТ" от 2014.02.03.docx',
                ),
                4
            ),
            'X-File-Name with UTF-8 encoded' => array(
                array(
                    'X-File-Name' => rawurlencode('Договор с ООО "ЕВРОАРТ" от 2014.02.03.docx'),
                ),
                201,
                array(
                    'name' => 'Договор с ООО "ЕВРОАРТ" от 2014.02.03.docx',
                ),
                4
            ),
        );
    }
}
