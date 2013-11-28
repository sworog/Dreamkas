<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ReportControllerTest extends WebTestCase
{
    public function testGetReportsWithoutCalculate()
    {
        $storeId = $this->factory->getStore();
        $accessToken = $this->factory->authAsStoreManager($storeId);
        $response = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/store/' . $storeId . '/report/grossSale'
        );


    }
}
