<?php

namespace Lighthouse\CoreBundle\Tests\Document\StoreProduct;

use JMS\Serializer\Serializer;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;

class StoreProductTest extends ContainerAwareTestCase
{
    public function testInventoryRoundings()
    {
        $storeProduct = new StoreProduct();
        $storeProduct->inventory = 152.1445;
        $storeProduct->averageDailySales = 12.3455;

        /* @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');

        $serialized = $serializer->serialize($storeProduct, 'json');
        $this->assertJson($serialized);

        $json = json_decode($serialized, true);

        $this->assertEquals('12.35', $json['averageDailySales']);
        $this->assertEquals('12.3', $json['inventoryDays']);
        $this->assertEquals('152.14', $json['inventory']);
    }
}
