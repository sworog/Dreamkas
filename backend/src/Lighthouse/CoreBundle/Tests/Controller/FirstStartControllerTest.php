<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class FirstStartControllerTest extends WebTestCase
{
    public function testGetFirstStartClean()
    {
        $accessToken = $this->factory()->oauth()->authAsProjectUser();

        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/firstStart'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathEquals(false, 'complete', $response);
        Assert::assertNotJsonHasPath('stores', $response);
    }
}
