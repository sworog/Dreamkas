<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class RoundingControllerTest extends WebTestCase
{
    public function testGetRoundingsAction()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/roundings'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(5, '*.name', $getResponse);

        Assert::assertJsonPathEquals('до копеек', '*.title', $getResponse, 1);
        Assert::assertJsonPathEquals('до рублей', '*.title', $getResponse, 1);
        Assert::assertJsonPathEquals('до 10 копеек', '*.title', $getResponse, 1);
        Assert::assertJsonPathEquals('до 50 копеек', '*.title', $getResponse, 1);
        Assert::assertJsonPathEquals('до 99 копеек', '*.title', $getResponse, 1);
    }
}
