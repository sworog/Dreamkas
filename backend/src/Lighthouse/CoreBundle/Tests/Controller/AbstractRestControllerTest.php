<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\WebTestCase;

class AbstractRestControllerTest extends WebTestCase
{
    public function testCORSHeader()
    {
        $postArray = array(
            'name' => 'Кефир',
        );

        $headers = array(
            'HTTP_Origin' => 'http://webfront.lighthouse.dev',
        );

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postArray,
            array(),
            $headers
        );

        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->has('Access-Control-Allow-Origin'));

        $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/products',
            $postArray
        );

        $response = $this->client->getResponse();
        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
    }
}
