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

    /**
     * @param string $name
     * @param string $title
     *
     * @dataProvider getRoundingActionDataProvider
     */
    public function testGetRoundingAction($name, $title)
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/roundings/' . $name
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals($name, 'name', $getResponse);
        Assert::assertJsonPathEquals($title, 'title', $getResponse);
    }

    /**
     * @return array
     */
    public function getRoundingActionDataProvider()
    {
        return array(
            array('nearest1', 'до копеек'),
            array('nearest10', 'до 10 копеек'),
            array('nearest50', 'до 50 копеек'),
            array('nearest100', 'до рублей'),
            array('fixed99', 'до 99 копеек'),
        );
    }

    public function testGetRoundingActionNotFound()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/roundings/fixed'
        );

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('not found', 'message', $getResponse);
    }
}
