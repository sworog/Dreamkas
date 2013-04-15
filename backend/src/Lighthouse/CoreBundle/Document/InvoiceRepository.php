<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

class InvoiceRepository extends DocumentRepository
{
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
            ->field('itemsCount')->inc($itemsCountDiff)
            ->field('sumTotal')->inc($sumTotalDiff)
            ->returnNew()
            ->getQuery();
        $query->execute();
    }
}
