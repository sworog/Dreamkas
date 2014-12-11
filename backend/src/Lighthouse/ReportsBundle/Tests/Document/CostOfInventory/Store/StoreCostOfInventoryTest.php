<?php

namespace Lighthouse\ReportsBundle\Tests\Document\CostOfInventory\Store;

use Lighthouse\IntegrationBundle\Test\ContainerAwareTestCase;
use Lighthouse\ReportsBundle\Document\CostOfInventory\Store\StoreCostOfInventoryRepository;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;

class StoreCostOfInventoryTest extends ContainerAwareTestCase
{
    public function testCalculate()
    {
        $this->clearMongoDb();
        $this->authenticateProject();

        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '-3 days'), $stores['1']->id)
                ->createInvoiceProduct($products['1']->id, 10, 50)
                ->createInvoiceProduct($products['2']->id, 20, 40)
                ->createInvoiceProduct($products['3']->id, 30, 30)
            ->persist()
                ->createInvoice(array('date' => '-2 days'), $stores['2']->id)
                ->createInvoiceProduct($products['1']->id, 15, 55)
                ->createInvoiceProduct($products['2']->id, 25, 45)
                ->createInvoiceProduct($products['3']->id, 35, 35)
            ->flush();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();

        $total = $this->getStoreCostOfInventoryRepository()->recalculate();
        $this->assertEquals(2, $total);

        $this->assertStoreReport($stores['1']->id, '2200.00');
        $this->assertStoreReport($stores['2']->id, '3175.00');

        $this->factory()
            ->receipt()
                ->createSale($stores['1'])
                ->createReceiptProduct($products['1']->id, 2, 69.99)
                ->createReceiptProduct($products['2']->id, 5.678, 44.49)
            ->persist()
                ->createSale($stores['2'])
                ->createReceiptProduct($products['2']->id, 5, 49.99)
                ->createReceiptProduct($products['3']->id, 2.333, 39.89)
            ->flush();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();

        $total = $this->getStoreCostOfInventoryRepository()->recalculate();
        $this->assertEquals(2, $total);

        $this->assertStoreReport($stores['1']->id, '1872.88');
        $this->assertStoreReport($stores['2']->id, '2868.34');
    }

    /**
     * @param $storeId
     * @param $expectedCostOfInventory
     */
    public function assertStoreReport($storeId, $expectedCostOfInventory)
    {
        $storeReport = $this->getStoreCostOfInventoryRepository()->find($storeId);
        $this->assertSame($expectedCostOfInventory, $storeReport->costOfInventory->toString());
    }

    /**
     * @return GrossMarginManager
     */
    protected function getGrossMarginManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
    }

    /**
     * @return StoreCostOfInventoryRepository
     */
    protected function getStoreCostOfInventoryRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.cost_of_inventory.store.repository');
    }
}
