<?php

namespace Lighthouse\CoreBundle\Document\Invoice;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class InvoiceRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param InvoicesFilter $filter
     * @return Cursor
     */
    public function findByStore($storeId, InvoicesFilter $filter)
    {
        $criteria = array('store' => $storeId);
        $sort = array('acceptanceDate' => self::SORT_DESC);
        if ($filter->hasSkuOrSupplierInvoiceSku()) {
            $criteria['$or'] = array(
                array('sku' => $filter->getSkuOrSupplierInvoiceSku()),
                array('supplierInvoiceSku' => $filter->getSkuOrSupplierInvoiceSku()),
            );
        }
        $cursor = $this->findBy($criteria, $sort);
        return $cursor;
    }

    /**
     * @param Invoice $invoice
     * @param int $itemsCountDiff
     * @param int $sumTotalDiff
     */
    public function updateTotals(Invoice $invoice, $itemsCountDiff, $sumTotalDiff)
    {
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

        $query->getQuery()->execute();
    }
}
