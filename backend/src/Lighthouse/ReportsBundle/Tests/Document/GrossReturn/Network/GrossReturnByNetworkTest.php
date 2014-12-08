<?php

namespace Document\GrossReturn\Network;

use DateTime;
use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
use Lighthouse\CoreBundle\Test\WebTestCase;
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
        /** @var GrossReturnNetwork $report */
        $report = $this->getGrossReturnRepository()->findOneByDay(new DateTime($day));
        $this->assertSame($grossReturn, $report->grossReturn->toString());
        $this->assertSame($quantity, $report->quantity->toString());
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
        $this->assertGrossReturnDay('-4 day 00:00:00', '350.00', '50.000');
    }
}
