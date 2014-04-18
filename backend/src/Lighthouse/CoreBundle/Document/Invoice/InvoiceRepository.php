<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class InvoiceRepository extends DocumentRepository
{
    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param NumericFactory $numericFactory
     */
    public function setNumericFactory(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }

    /**
     * @return Invoice
     */
    public function createNew()
    {
        $invoice = new Invoice();
        $invoice->sumTotal = $this->numericFactory->createMoney(null);
        $invoice->totalAmountVAT = $this->numericFactory->createMoney(null);
        $invoice->sumTotalWithoutVAT = $this->numericFactory->createMoney(null);

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
     * @param InvoicesFilter $filter
     * @return Cursor
     */
    public function findByStore($storeId, InvoicesFilter $filter)
    {
        $criteria = array('store' => $storeId);
        $sort = array('acceptanceDate' => self::SORT_DESC);
        if ($filter->hasNumberOrSupplierInvoiceNumber()) {
            $criteria['$or'] = array(
                array('number' => $filter->getNumberOrSupplierInvoiceNumber()),
                array('supplierInvoiceNumber' => $filter->getNumberOrSupplierInvoiceNumber()),
            );
        }
        $cursor = $this->findBy($criteria, $sort);
        return $cursor;
    }

    /**
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
