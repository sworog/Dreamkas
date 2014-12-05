<?php

namespace Document\GrossReturn\Network;

use Lighthouse\CoreBundle\Test\Console\ApplicationTester;
use Lighthouse\CoreBundle\Test\WebTestCase;

class GrossReturnByNetworkTest extends WebTestCase
{
    /**
     * @return ApplicationTester
     */
    protected function runRecalculateCommand()
    {
        return $this->createConsoleTester(false, true)->runCommand('lighthouse:reports:recalculate');
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
                ->createReturn(null, '-4 day 3:44:45')
                ->createReceiptProduct($product->id, 50, 7)
            ->flush();

        $this->runRecalculateCommand();


    }
}
