<?php

namespace Lighthouse\ReportsBundle\Tests\Document\Gross\Hour\Network;

use Lighthouse\IntegrationBundle\Test\ContainerAwareTestCase;
use Lighthouse\ReportsBundle\Document\Gross\Hour\Network\GrossHourNetworkRepository;
use Lighthouse\ReportsBundle\Reports\GrossMargin\GrossMarginManager;

class HourGrossNetworkTest extends ContainerAwareTestCase
{
    /**
     * @return GrossHourNetworkRepository
     */
    protected function getGrossHourNetworkRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross.hour.network.repository');
    }

    /**
     * @return GrossMarginManager
     */
    protected function getGrossMarginManager()
    {
        return $this->getContainer()->get('lighthouse.reports.gross_margin.manager');
    }

    protected function prepareData()
    {
        $products = $this->factory()->catalog()->getProductByNames(array('1', '2', '3'));
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '2014-12-01 00:00:00'), $stores['1']->id)
                ->createInvoiceProduct($products['1']->id, 100, 8.67)
                ->createInvoiceProduct($products['2']->id, 100, 8.77)
                ->createInvoiceProduct($products['3']->id, 100, 8.87)
            ->persist()
                ->createInvoice(array('date' => '2014-12-01 00:00:00'), $stores['2']->id)
                ->createInvoiceProduct($products['1']->id, 100, 8.67)
                ->createInvoiceProduct($products['2']->id, 100, 8.77)
                ->createInvoiceProduct($products['3']->id, 100, 8.87)
            ->persist()
                ->createInvoice(array('date' => '2014-12-01 00:00:00'), $stores['3']->id)
                ->createInvoiceProduct($products['1']->id, 100, 8.67)
                ->createInvoiceProduct($products['2']->id, 100, 8.77)
                ->createInvoiceProduct($products['3']->id, 100, 8.87)
            ->flush();

        $this->factory()
            ->receipt()
                ->createSale($stores['1'], '2014-12-01 09:59:59')
                ->createReceiptProduct($products['1']->id, 1, 9.59)
                ->createReceiptProduct($products['2']->id, 2, 10.59)
                ->createReceiptProduct($products['3']->id, 3, 11.59)
            ->persist()
                ->createSale($stores['2'], '2014-12-01 10:30:30')
                ->createReceiptProduct($products['1']->id, 3, 9.59)
                ->createReceiptProduct($products['2']->id, 2, 10.59)
                ->createReceiptProduct($products['3']->id, 1, 11.59)
            ->persist()
                ->createSale($stores['3'], '2014-12-01 17:00:00')
                ->createReceiptProduct($products['1']->id, 2, 9.59)
                ->createReceiptProduct($products['2']->id, 2, 10.59)
                ->createReceiptProduct($products['3']->id, 2, 11.59)
            ->persist()
                ->createSale($stores['3'], '2014-12-01 23:59:59')
                ->createReceiptProduct($products['1']->id, 1, 9.59)
                ->createReceiptProduct($products['2']->id, 2, 10.59)
                ->createReceiptProduct($products['3']->id, 3, 11.59)
            ->persist()
                ->createSale($stores['2'], '2014-12-02 01:15:15')
                ->createReceiptProduct($products['1']->id, 1, 9.59)
                ->createReceiptProduct($products['2']->id, 2, 10.59)
                ->createReceiptProduct($products['3']->id, 3, 11.59)
            ->persist()
                ->createSale($stores['1'], '2014-12-03 18:34:43')
                ->createReceiptProduct($products['1']->id, 1.122, 9.59)
                ->createReceiptProduct($products['2']->id, 2.123, 10.59)
                ->createReceiptProduct($products['3']->id, 3.321, 11.59)
            ->flush();
    }

    public function testAggregateOut()
    {
        $this->clearMongoDb();
        $this->authenticateProject();
        $this->prepareData();

        $this->getGrossMarginManager()->calculateGrossMarginUnprocessedTrialBalance();

        $this->getGrossHourNetworkRepository()->recalculate();

        $grossHourNetworks = $this->getGrossHourNetworkRepository()->findAll();

        foreach ($grossHourNetworks as $grossHourNetwork) {
            $this->assertNotNull($grossHourNetwork->grossSales->toString());
        }
    }
}
