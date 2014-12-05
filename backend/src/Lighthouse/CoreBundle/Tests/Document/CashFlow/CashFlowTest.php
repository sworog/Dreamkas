<?php

namespace Lighthouse\CoreBundle\Tests\Document\CashFlow;

use DateTime;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlow;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlowRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;

class CashFlowTest extends WebTestCase
{
    /**
     * @return CashFlowRepository
     */
    protected function getCashFlowRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.cash_flow');
    }

    public function testAutoCreateCashFlowOnCreateAndEditInvoice()
    {
        $user = $this->factory()->user()->getProjectUser();
        $this->getContainer()->get('project.context')->authenticateByUser($user);

        $product = $this->factory()->catalog()->getProduct();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array('paid' => true))
                ->createInvoiceProduct($product->id, 100, 7)
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('700.00'), 'Amount not equals expected');
        $this->assertEquals('out', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);

        $this->factory()
            ->invoice()
                ->editInvoice($invoice->id, array('paid' => false))
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(0, $cashFlowsCursor);

        $this->factory()->invoice()
            ->editInvoice($invoice->id, array('paid' => true))
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('700.00'), 'Amount not equals expected');
        $this->assertEquals('out', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);
    }

    public function testAutoChangeAmountCashFlowOnEditInvoice()
    {
        $user = $this->factory()->user()->getProjectUser();
        $this->getContainer()->get('project.context')->authenticateByUser($user);

        $product = $this->factory()->catalog()->getProduct();

        $invoice = $this->factory()
            ->invoice()
                ->createInvoice(array('paid' => true))
                ->createInvoiceProduct($product->id, 100, 7)
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('700.00'), 'Amount not equals expected');
        $this->assertEquals('out', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);

        $this->factory()
            ->invoice()
                ->editInvoice($invoice->id)
                ->editInvoiceProduct(0, $product->id, 50, 5)
            ->flush();

        $this->getCashFlowRepository()->getDocumentManager()->clear();
        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('250.00'), 'Amount not equals expected');
        $this->assertEquals('out', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);
    }

    public function testDeleteCashFromAfterDeleteInvoice()
    {
        $user = $this->factory()->user()->getProjectUser();
        $this->getContainer()->get('project.context')->authenticateByUser($user);

        $product = $this->factory()->catalog()->getProduct();

        $invoice = $this->factory()
            ->invoice()
            ->createInvoice(array('paid' => true))
            ->createInvoiceProduct($product->id, 100, 7)
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('700.00'), 'Amount not equals expected');
        $this->assertEquals('out', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);

        $this->factory()->invoice()->removeInvoice($invoice->id);

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(0, $cashFlowsCursor);
    }

    public function testAutoCreateCashFlowOnCreateAndEditSupplierReturn()
    {
        $user = $this->factory()->user()->getProjectUser();
        $this->getContainer()->get('project.context')->authenticateByUser($user);

        $product = $this->factory()->catalog()->getProduct();

        $supplierReturn = $this->factory()
            ->supplierReturn()
            ->createSupplierReturn(null, null, null, true)
            ->createSupplierReturnProduct($product->id, 100, 7)
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('700.00'), 'Amount not equals expected');
        $this->assertEquals('in', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);

        $this->factory()
            ->supplierReturn()
                ->editSupplierReturn($supplierReturn->id, null, null, null, false)
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(0, $cashFlowsCursor);

        $this->factory()->supplierReturn()
            ->editSupplierReturn($supplierReturn->id, null, null, null, true)
            ->flush();

        $this->getCashFlowRepository()->getDocumentManager()->clear();
        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('700.00'), 'Amount not equals expected');
        $this->assertEquals('in', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);
    }

    public function testAutoChangeAmountCashFlowOnEditSupplierReturn()
    {
        $user = $this->factory()->user()->getProjectUser();
        $this->getContainer()->get('project.context')->authenticateByUser($user);

        $product = $this->factory()->catalog()->getProduct();

        $supplierReturn = $this->factory()
            ->supplierReturn()
            ->createSupplierReturn(null, null, null, true)
            ->createSupplierReturnProduct($product->id, 100, 7)
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertTrue($cashFlow->amount->equals('700.00'), 'Amount not equals expected');
        $this->assertEquals('in', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);

        $this->factory()
            ->supplierReturn()
                ->editSupplierReturn($supplierReturn->id)
                ->editSupplierReturnProduct(0, $product->id, 50, 5)
            ->flush();

        $this->getCashFlowRepository()->getDocumentManager()->clear();
        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertSame('250.00', $cashFlow->amount->toString(), 'Amount not equals expected');
        $this->assertEquals('in', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);
    }

    public function testDeleteCashFromAfterDeleteSupplierReturn()
    {
        $this->authenticateProject();

        $product = $this->factory()->catalog()->getProduct();

        $supplierReturn = $this->factory()
            ->supplierReturn()
                ->createSupplierReturn(null, null, null, true)
                ->createSupplierReturnProduct($product->id, 100, 7)
            ->flush();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(1, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertSame('700.00', $cashFlow->amount->toString(), 'Amount not equals expected');
        $this->assertEquals('in', $cashFlow->direction);
        $this->assertEquals(new DateTime("00:00:00"), $cashFlow->date);

        $this->factory()->supplierReturn()->removeSupplierReturn($supplierReturn->id);
        
        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(0, $cashFlowsCursor);
    }

    public function testSalesByDay()
    {
        $product = $this->factory()->catalog()->getProduct();

        $this->factory()
            ->receipt()
                ->createSale(null, '-5 day 05:33:33')
                ->createReceiptProduct($product->id, 100.1, 7)
            ->persist()
                ->createSale(null, '-5 day 12:15:45')
                ->createReceiptProduct($product->id, 100.1, 7)
            ->persist()
                ->createSale(null, '-6 day 15:33:45')
                ->createReceiptProduct($product->id, 77, 10)
            ->flush();

        $this->createConsoleTester(false, true)->runCommand('lighthouse:reports:recalculate');

        $this->authenticateProject();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(2, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertSame('1401.40', $cashFlow->amount->toString(), 'Amount not equals expected');
        $this->assertEquals('in', $cashFlow->direction);
        $this->assertEquals(new DateTime("-5 day 00:00:00"), $cashFlow->date);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertSame('770.00', $cashFlow->amount->toString(), 'Amount not equals expected');
        $this->assertEquals('in', $cashFlow->direction);
        $this->assertEquals(new DateTime("-6 day 00:00:00"), $cashFlow->date);
    }

    public function testReturnsByDay()
    {
        $product = $this->factory()->catalog()->getProduct();

        $this->factory()
            ->receipt()
                ->createReturn(null, '-5 day 05:33:33')
                ->createReceiptProduct($product->id, 100.1, 7)
            ->persist()
                ->createReturn(null, '-5 day 12:15:45')
                ->createReceiptProduct($product->id, 100.1, 7)
            ->persist()
                ->createReturn(null, '-6 day 15:33:45')
                ->createReceiptProduct($product->id, 77, 10)
            ->flush();

        $this->createConsoleTester(false, true)->runCommand('lighthouse:reports:recalculate');

        $this->authenticateProject();

        $cashFlowsCursor = $this->getCashFlowRepository()->findAll();
        $this->assertCount(2, $cashFlowsCursor);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertSame('1401.40', $cashFlow->amount->toString(), 'Amount not equals expected');
        $this->assertEquals('out', $cashFlow->direction);
        $this->assertEquals(new DateTime("-5 day 00:00:00"), $cashFlow->date);

        /** @var CashFlow $cashFlow */
        $cashFlow = $cashFlowsCursor->getNext();

        $this->assertSame('770.00', $cashFlow->amount->toString(), 'Amount not equals expected');
        $this->assertEquals('out', $cashFlow->direction);
        $this->assertEquals(new DateTime("-6 day 00:00:00"), $cashFlow->date);
    }
}
