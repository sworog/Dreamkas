<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ServiceControllerTest extends WebTestCase
{
    public function testRecalculateAveragePurchasePriceAction()
    {
        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/service/recalculate-average-purchase-price'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals(true, 'ok', $response);
    }

    public function testPermissionsAction()
    {
        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/service/permissions'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('ROLE_ADMINISTRATOR', 'users.GET::{user}.*', $response);
        Assert::assertJsonPathEquals('ROLE_ADMINISTRATOR', 'users.POST.*', $response);
    }
}
