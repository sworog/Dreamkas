<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ServiceControllerTest extends WebTestCase
{
    public function testRecalculateAveragePurchasePriceAction()
    {
        $this->clearMongoDb();

        $client = static::createClient();
        $response = $this->clientJsonRequest(
            $client,
            'GET',
            '/api/1/service/recalculate-average-purchase-price'
        );

        Assert::assertResponseCode(200, $client);
        Assert::assertJsonPathEquals(true, 'ok', $response);
    }

    public function testPermissionsAction()
    {
        $this->clearMongoDb();

        $client = static::createClient();
        $response = $this->clientJsonRequest(
            $client,
            'GET',
            '/api/1/service/permissions'
        );

        Assert::assertResponseCode(200, $client);
        Assert::assertJsonPathEquals('administrator', 'users.GET::{user}.*', $response);
        Assert::assertJsonPathEquals(false, 'users.POST', $response);
    }
}
