<?php

namespace Lighthouse\ReportsBundle\Tests\Document\GrossMarginSales\Network;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Network\GrossMarginSalesNetwork;

class GrossMarginSalesNetworkTest extends ContainerAwareTestCase
{
    public function testGetItemIsNull()
    {
        $grossMarginSalesNetwork = new GrossMarginSalesNetwork();
        $this->assertNull($grossMarginSalesNetwork->getItem());
    }
}
