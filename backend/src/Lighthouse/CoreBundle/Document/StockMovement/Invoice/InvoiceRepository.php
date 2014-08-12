<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Invoice;

use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\LockMode;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

/**
 * @method Invoice find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 */
class InvoiceRepository extends StockMovementRepository
{
    /**
     * @return Invoice
     */
    public function createNew()
    {
        $invoice = new Invoice();
        $invoice->sumTotal = $this->numericFactory->createMoney();
        $invoice->totalAmountVAT = $this->numericFactory->createMoney();
        $invoice->sumTotalWithoutVAT = $this->numericFactory->createMoney();

        return $invoice;
    }

    /**
     * @param Order $order
     * @return Invoice
     */
    public function createByOrder(Order $order)
    {
        $invoice = $this->createNew();

        $invoice->order = $order;
        $invoice->store = $order->store;
        $invoice->supplier = $order->supplier;

        foreach ($order->products as $orderProduct) {
            $invoiceProduct = new InvoiceProduct();
            $invoiceProduct->quantity = $orderProduct->quantity;
            $invoiceProduct->product = $orderProduct->product;
            $invoiceProduct->priceEntered = $orderProduct->product->purchasePrice;
            $invoiceProduct->invoice = $invoice;

            $invoice->products->add($invoiceProduct);
        }

        $invoice->calculateTotals();

        return $invoice;
    }

    /**
     * @param string $storeId
     * @param InvoiceFilter $filter
     * @return Cursor|Invoice[]
     */
    public function findByStore($storeId, InvoiceFilter $filter = null)
    {
        $criteria = array(
            'store' => $storeId,
        );
        $sort = array('date' => self::SORT_DESC);
        if ($filter && $filter->hasNumberOrSupplierInvoiceNumber()) {
            $criteria['$or'] = array(
                array('number' => $filter->getNumberOrSupplierInvoiceNumber()),
                array('supplierInvoiceNumber' => $filter->getNumberOrSupplierInvoiceNumber()),
            );
        }
        return $this->findBy($criteria, $sort);
    }

    /**
     * @deprecated
     *
     * @param Invoice $invoice
     * @param int $itemsCountDiff
     * @param int $sumTotalDiff
     * @param int $sumTotalWithoutVATDiff
     * @param int $totalAmountVATDiff
     */
    public function updateTotals(
        Invoice $invoice,
        $itemsCountDiff,
        $sumTotalDiff,
        $sumTotalWithoutVATDiff,
        $totalAmountVATDiff
    ) {
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->field('id')->equals($invoice->id)
            ->returnNew();

        if ($itemsCountDiff <> 0) {
            $query->field('itemsCount')->inc($itemsCountDiff);
        }

        if ($sumTotalDiff <> 0) {
            $query->field('sumTotal')->inc($sumTotalDiff);
        }

        if ($sumTotalWithoutVATDiff <> 0) {
            $query->field('sumTotalWithoutVAT')->inc($sumTotalWithoutVATDiff);
        }

        if ($totalAmountVATDiff <> 0) {
            $query->field('totalAmountVAT')->inc($totalAmountVATDiff);
        }

        $query->getQuery()->execute();
    }
}
