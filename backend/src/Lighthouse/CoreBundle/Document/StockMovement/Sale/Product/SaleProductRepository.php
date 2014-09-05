<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Sale\Product;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Product\SaleProduct;

class SaleProductRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor|SaleProduct[]
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
