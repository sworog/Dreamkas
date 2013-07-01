<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ServiceControllerTest extends WebTestCase
{
    public function testRecalculateAveragePurchasePriceAction()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/service/recalculate-average-purchase-price'
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathEquals(true, 'ok', $response);
    }

    public function testPermissionsAction()
    {
        $this->clearMongoDb();

        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/service/permissions'
        );

        Assert::assertResponseCode(200, $this->client);
        Assert::assertJsonPathEquals('ROLE_ADMINISTRATOR', 'users.GET::{user}.*', $response);
        Assert::assertJsonPathEquals('ROLE_ADMINISTRATOR', 'users.POST.*', $response);
    }
}
