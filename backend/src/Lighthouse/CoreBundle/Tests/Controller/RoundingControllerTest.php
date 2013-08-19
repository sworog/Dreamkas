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
            'nearest1' => array('nearest1', 'до копеек'),
            'nearest10' => array('nearest10', 'до 10 копеек'),
            'nearest50' => array('nearest50', 'до 50 копеек'),
            'nearest100' => array('nearest100', 'до рублей'),
            'nearest99' => array('nearest99', 'до 99 копеек'),
        );
    }

    public function testGetRoundingActionNotFound()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/roundings/aaaa'
        );

        $this->assertResponseCode(404);

        Assert::assertJsonPathContains('not found', 'message', $getResponse);
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
            'nearest1 up' => array('nearest1', '23.657', '23.66'),
            'nearest1 up #2' => array('nearest1', '23.655', '23.66'),
            'nearest1 down' => array('nearest1', '23.654', '23.65'),
            'nearest10 up' => array('nearest10', '23.654', '23.70'),
            'nearest10 down' => array('nearest10', '23.644', '23.60'),
            'nearest50 down' => array('nearest50', '23.744', '23.50'),
            'nearest50 up' => array('nearest50', '23.751', '24.00'),
            'nearest100 down' => array('nearest100', '23.744', '24.00'),
            'nearest100 up' => array('nearest100', '23.455', '23.00'),
            'nearest99 down' => array('nearest99', '23.744', '23.99'),
            'nearest99 up' => array('nearest99', '23.455', '22.99'),
        );
    }

    /**
     * @param string $price
     * @param array $assertions
     *
     * @dataProvider postRoundingRoundActionInvalidDataProvider
     */
    public function testPostRoundingRoundActionInvalidData($price, array $assertions)
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole(User::ROLE_STORE_MANAGER);

        $postData = array(
            'price' => $price,
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/roundings/nearest1/round',
            $postData
        );

        $this->assertResponseCode(400);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @return array
     */
    public function postRoundingRoundActionInvalidDataProvider()
    {
        return array(
            'invalid string' => array(
                'aaaa',
                array(
                    'children.price.errors.0' => 'Цена не должна быть меньше или равна нулю.'
                )
            ),
            /*
            'no price' => array(
                null,
                array(
                    'children.price.errors.0' => 'a'
                )
            ),
            'empty price' => array(
                '',
                array(
                    'children.price.errors.0' => 'a'
                )
            )
            */
        );
    }
}
