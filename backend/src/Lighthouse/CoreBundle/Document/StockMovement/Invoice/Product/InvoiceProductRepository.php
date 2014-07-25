<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Invoice\Product;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;

class InvoiceProductRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor|InvoiceProduct[]
     */
    public function findByStoreAndProduct($storeId, $productId)
    {
        $criteria = array(
            'store' => $storeId,
            'originalProduct' => $productId,
        );
        $sort = array(
            'date' => self::SORT_DESC,
        );
        return $this->findBy($criteria, $sort);
    }

    /**
     * @param string $storeId
     * @return Cursor|InvoiceProduct[]
     */
    public function findByStoreId($storeId)
    {
        $criteria = array(
            'store' => $storeId,
        );
        $sort = array(
            'date' => self::SORT_DESC,
        );
        return $this->findBy($criteria, $sort);
    }

    /**
     * @param Invoice $invoice
     * @return bool
     */
    public function recalcVATByInvoice(Invoice $invoice)
    {
        $invoiceProducts = $invoice->products;
        if ($invoiceProducts->count() > 0) {
            foreach ($invoiceProducts as $invoiceProduct) {
                $invoiceProduct->calculatePrices();
                $this->getDocumentManager()->persist($invoiceProduct);
            }
            $this->getDocumentManager()->flush();
        }
        return true;
    }
}
