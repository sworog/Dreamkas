<?php

namespace Lighthouse\CoreBundle\Document\Invoice\Product;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;

class InvoiceProductRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor
     */
    public function findByStoreAndProduct($storeId, $productId)
    {
        $criteria = array(
            'store' => $storeId,
            'originalProduct' => $productId,
        );
        $sort = array(
            'acceptanceDate' => self::SORT_DESC,
        );
        return $this->findBy($criteria, $sort);
    }

    /**
     * @param string|Invoice $invoice
     * @return Cursor
     */
    public function findByInvoice($invoice)
    {
        if ($invoice instanceof Invoice) {
            $invoiceId = $invoice->id;
        } else {
            $invoiceId = $invoice;
        }

        return $this->findBy(array('invoice' => $invoiceId));
    }

    /**
     * @param string|Invoice $invoice
     * @return bool
     */
    public function recalcVATByInvoice($invoice)
    {
        $invoiceProducts = $this->findByInvoice($invoice);
        if (count($invoiceProducts) == 0) {
            return true;
        }
        foreach ($invoiceProducts as $invoiceProduct) {
            /** @var $invoiceProduct InvoiceProduct */
            $invoiceProduct->calculatePrices();
            $this->getDocumentManager()->persist($invoiceProduct);
        }

        $this->getDocumentManager()->flush();
        return true;
    }
}
