<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class FirstStartControllerTest extends WebTestCase
{
    public function testGetFirstStartStore()
    {
        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/firstStart'
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(false, 'complete', $response);
        Assert::assertJsonPathCount(0, 'stores.*', $response);

        $store1 = $this->factory()->store()->getStore('1');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/firstStart'
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(false, 'complete', $response);
        Assert::assertJsonPathEquals($store1->id, 'stores.0.store.id', $response, 1);
        Assert::assertJsonPathCount(1, 'stores.*', $response);

        $store2 = $this->factory()->store()->getStore('2');

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/firstStart'
        );

        $this->assertResponseCode(200);

        Assert::assertNotJsonHasPath('id', $response);
        Assert::assertJsonPathEquals(false, 'complete', $response);
        Assert::assertJsonPathEquals($store1->id, 'stores.0.store.id', $response, 1);
        Assert::assertJsonPathEquals($store2->id, 'stores.1.store.id', $response, 1);
        Assert::assertJsonPathCount(2, 'stores.*', $response);
    }
}
