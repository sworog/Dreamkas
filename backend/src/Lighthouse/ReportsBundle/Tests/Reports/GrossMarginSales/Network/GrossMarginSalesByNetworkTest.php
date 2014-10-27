<?php

namespace Lighthouse\ReportsBundle\Tests\Reports\GrossMarginSales\Network;

use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Network\GrossMarginSalesByNetwork;

class GrossMarginSalesByNetworkTest extends ContainerAwareTestCase
{
    public function testGetItemIsNull()
    {
        $numericFactory = $this->getContainer()->get('lighthouse.core.types.numeric.factory');
        $report = new GrossMarginSalesByNetwork($numericFactory, array());
        $this->assertNull($report->getItem());
    }
}
