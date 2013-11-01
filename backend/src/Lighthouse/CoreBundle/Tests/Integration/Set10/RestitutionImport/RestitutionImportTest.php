<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Set10\ImportRestitution;

use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Tests\Integration\IntegrationTestCase;

class RestitutionImportTest extends IntegrationTestCase
{
    public function testRestitutionImport()
    {
        $storeId = $this->createStore('197');

        $skuAmounts = array(
            '1' => 1,
            '2' => 0,
            '3' => 24,
            '4' => -23,
        );

        $productIds = $this->createProductsBySku(array_keys($skuAmounts));

        $output = new TestOutput();
        $this->import('Integration/Set10/RestitutionImport/purchases-with-restitution.xml', $output);

        $this->assertStringStartsWith('....', $output->getDisplay());
        $lines = $output->getLines();
        $this->assertCount(1, $lines);

        foreach ($skuAmounts as $sku => $amount) {
            $this->assertStoreProductTotals($storeId, $productIds[$sku], $amount);
        }
    }
}
