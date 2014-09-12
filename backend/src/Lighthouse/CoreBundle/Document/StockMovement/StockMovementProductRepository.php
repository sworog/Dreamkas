<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class StockMovementProductRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor|StockMovementProduct[]
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
     * @return Cursor|StockMovementProduct[]
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
}
