<?php

namespace Lighthouse\CoreBundle\Tests\Serializer;

use Lighthouse\CoreBundle\Test\WebTestCase;

class StoreProductMetricsCalculatorTest extends WebTestCase
{
    public function testInventoryRatioCalculation()
    {
        $storeId = $this->factory->getStore();
        $productId1 = $this->createProduct('1');
        $productId2 = $this->createProduct('2');
    }
}
