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
                array('number' => $filter->getSkuOrSupplierInvoiceSku()),
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
