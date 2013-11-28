<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ReportControllerTest extends WebTestCase
{
    public function testGetReportsWithoutCalculate()
    {
        $storeManager = $this->createUser('storeManager', 'password', User::ROLE_STORE_MANAGER);
        $storeId = $this->createStore();
        $this->factory->linkStoreManagers($storeManager->id, $storeId);


        $accessToken = $this->auth($storeManager, 'password');
        $response = $this->clientJsonRequest(
            $storeManager,
            'GET',
            '/api/1/store/' . $storeId . '/report/grossSale'
        );


    }
}
