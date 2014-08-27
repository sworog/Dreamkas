<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\Product;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;

class SupplierReturnProductRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor|SupplierReturn[]
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
}
