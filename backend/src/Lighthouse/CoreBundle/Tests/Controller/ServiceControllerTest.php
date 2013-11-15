<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ServiceControllerTest extends WebTestCase
{
    public function testRecalculateAveragePurchasePriceActionNotFound()
    {
        $accessToken = $this->authAsRole('ROLE_ADMINISTRATOR');

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/service/recalculate-average-purchase-price'
        );

        $this->assertResponseCode(404);
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
