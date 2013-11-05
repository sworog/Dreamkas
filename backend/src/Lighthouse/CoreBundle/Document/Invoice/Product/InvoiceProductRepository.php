<?php

namespace Lighthouse\CoreBundle\Document\Invoice\Product;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

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
     * @param $invoiceId
     * @return Cursor
     */
    public function findByInvoice($invoiceId)
    {
        return $this->findBy(array('invoice' => $invoiceId));
    }
}
