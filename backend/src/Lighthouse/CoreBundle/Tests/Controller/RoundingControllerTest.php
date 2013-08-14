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

    /**
     * @param string $name
     * @param string $price
     * @param string $roundedPrice
     *
     * @dataProvider postRoundingRoundActionDataProvider
     */
    public function testPostRoundingRoundAction($name, $price, $roundedPrice)
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $postData = array(
            'price' => $price,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/roundings/' . $name . '/round',
            $postData
        );

        $this->assertResponseCode(201);

        Assert::assertJsonPathContains($roundedPrice, 'price', $postResponse);
    }

    /**
     * @return array
     */
    public function postRoundingRoundActionDataProvider()
    {
        return array(
            array('nearest1', '23.657', '23.66'),
            array('nearest1', '23.655', '23.66'),
            array('nearest1', '23.654', '23.65'),
            array('nearest10', '23.654', '23.70'),
            array('nearest10', '23.644', '23.60'),
        );
    }
}
