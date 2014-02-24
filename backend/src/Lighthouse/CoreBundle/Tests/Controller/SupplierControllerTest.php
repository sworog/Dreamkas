<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class SupplierControllerTest extends WebTestCase
{
    public function testPost()
    {
        $accessToken = $this->factory->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postData = array(
            'name' => 'ООО "ЕвроАрт"'
        );
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/suppliers',
            $postData
        );
        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals($postData['name'], 'name', $postResponse);
    }
}
