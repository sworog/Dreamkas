<?php

namespace Document\GrossReturn\Network;

use DateTime;
use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\ReportsBundle\Document\GrossReturn\Network\GrossReturnNetwork;
use Lighthouse\ReportsBundle\Document\GrossReturn\Network\GrossReturnNetworkRepository;

class GrossReturnByNetworkTest extends WebTestCase
{
    /**
     * @return ApplicationTester
     */
    protected function runRecalculateCommand()
    {
        return $this->createConsoleTester(false, true)->runCommand('lighthouse:reports:recalculate');
    }

    /**
     * @return GrossReturnNetworkRepository
     */
    protected function getGrossReturnRepository()
    {
        return $this->getContainer()->get('lighthouse.reports.document.gross_return.network.repository');
    }

    /**
     * @param string $day
     * @param string|float $grossReturn
     * @param string|float $quantity
     */
    protected function assertGrossReturnDay(
        $day,
        $grossReturn,
        $quantity
    ) {
        $day = new DateTimestamp($day);
        $day->setTime(0, 0, 0);
        /** @var GrossReturnNetwork $report */
        $report = $this->getGrossReturnRepository()->findOneByDay($day);
        $this->assertSame($grossReturn, $report->grossReturn->toString());
        $this->assertSame($quantity, $report->quantity->toString());
    }

    /**
     * @param string $day
     */
    public function assertGrossReturnDayNotFound($day)
    {
        $day = new DateTimestamp($day);
        $day->setTime(0, 0, 0);
        $report = $this->getGrossReturnRepository()->findOneByDay($day);

        $this->assertNull($report);
    }

    public function testNetworkGrossReturnSimple()
    {
        $product = $this->factory()->catalog()->getProduct();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '-6 day'))
                ->createInvoiceProduct($product->id, 100, 5)
            ->flush();

        $sale1 = $this->factory()
            ->receipt()
                ->createSale(null, '-5 day 12:12:12')
                ->createReceiptProduct($product->id, 100, 7)
            ->flush();

        $this->factory()
            ->receipt()
                ->createReturn(null, '-4 day 3:44:45', $sale1)
                ->createReceiptProduct($product->id, 50, 7)
            ->flush();

        $this->runRecalculateCommand();

        $this->authenticateProject();
        $this->assertGrossReturnDay('-4 day', '350.00', '50.000');
    }

    public function testCalculate()
    {
        $stores = $this->factory()->store()->getStores(array('1', '2', '3'));

        $groups = $this->factory()->catalog()->getSubCategories(array('1', '2', '3'));

        $product1 = $this->factory()->catalog()->getProductByName('1.1', $groups['1']);
        $product2 = $this->factory()->catalog()->getProductByName('1.2', $groups['1']);
        $product3 = $this->factory()->catalog()->getProductByName('2.3', $groups['2']);

        $this->factory()
            ->invoice()
                ->createInvoice(array('date' => '-7 days'), $stores['1']->id)
                ->createInvoiceProduct($product1->id, 10, 20)
                ->createInvoiceProduct($product2->id, 5, 25)
                ->createInvoiceProduct($product3->id, 7, 14)
            ->persist()
                ->createInvoice(array('date' => '-7 days'), $stores['2']->id)
                ->createInvoiceProduct($product1->id, 11, 21)
                ->createInvoiceProduct($product2->id, 6, 26)
            ->flush();

        $sale1 = $this->factory()
            ->receipt()
                ->createSale($stores['1'], '-1 days 11:23')
                ->createReceiptProduct($product1->id, 1, 30)
                ->createReceiptProduct($product2->id, 2.5, 31.09)
            ->flush();
        $this->factory()
            ->receipt()
                ->createReturn($stores['1'], '-1 days 11:27', $sale1)
                ->createReceiptProduct($product1->id, 1)
                ->createReceiptProduct($product2->id, 2.5)
            ->flush();

        $sale2 = $this->factory()
            ->receipt()
                ->createSale($stores['1'], '-1 days 19:30')
                ->createReceiptProduct($product1->id, 2, 30)
                ->createReceiptProduct($product3->id, 5, 15)
            ->flush();
        $this->factory()
            ->receipt()
                ->createReturn($stores['1'], '-1 days 19:34', $sale2)
                ->createReceiptProduct($product1->id, 2)
                ->createReceiptProduct($product3->id, 5)
            ->flush();

        $sale3 = $this->factory()
            ->receipt()
                ->createSale($stores['2'], '-1 days 09:56')
                ->createReceiptProduct($product1->id, 6, 36)
                ->createReceiptProduct($product2->id, 3.5, 33.39)
            ->flush();
        $this->factory()
            ->receipt()
                ->createReturn($stores['2'], '-1 days 09:59', $sale3)
                ->createReceiptProduct($product1->id, 6)
                ->createReceiptProduct($product2->id, 3.5)
            ->flush();

        $sale4 = $this->factory()
            ->receipt()
                ->createSale($stores['1'], '-2 days 19:13')
                ->createReceiptProduct($product1->id, 2, 31)
                ->createReceiptProduct($product2->id, 2.5, 31.09)
            ->flush();
        $this->factory()
            ->receipt()
                ->createReturn($stores['1'], '-2 days 19:17', $sale4)
                ->createReceiptProduct($product1->id, 2)
                ->createReceiptProduct($product2->id, 2.5)
            ->flush();

        $sale5 = $this->factory()
            ->receipt()
                ->createSale($stores['2'], '-3 days 23:56')
                ->createReceiptProduct($product1->id, 3, 29)
                ->createReceiptProduct($product2->id, 1, 30.09)
            ->flush();
        $this->factory()
            ->receipt()
                ->createReturn($stores['2'], '-3 days 23:59', $sale5)
                ->createReceiptProduct($product1->id, 3)
                ->createReceiptProduct($product2->id, 1)
            ->flush();

        $sale6 = $this->factory()
            ->receipt()
                ->createSale($stores['1'], '-5 days 00:11')
                ->createReceiptProduct($product1->id, 1, 30)
                ->createReceiptProduct($product2->id, 1, 30)
            ->flush();
        $this->factory()
            ->receipt()
                ->createReturn($stores['1'], '-5 days 00:15', $sale6)
                ->createReceiptProduct($product1->id, 1, 30)
                ->createReceiptProduct($product2->id, 1, 30)
            ->flush();

        $this->runRecalculateCommand();

        $this->authenticateProject();

        $this->assertGrossReturnDayNotFound('today');

        $this->assertGrossReturnDay('-1 days', '575.60', '20.000');
        $this->assertGrossReturnDay('-2 days', '139.73', '4.500');
        $this->assertGrossReturnDay('-3 days', '117.09', '4.000');
        $this->assertGrossReturnDayNotFound('-4 days');
        $this->assertGrossReturnDay('-5 days', '60.00', '2.000');
    }
}
