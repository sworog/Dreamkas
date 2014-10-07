<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class StockMovementProductRepository extends DocumentRepository
{
    /**
     * @param string|null $storeId
     * @param string|null $productId
     * @param string|\DateTime|null $dateFrom
     * @param string|\DateTime|null $dateTo
     * @return \Doctrine\ODM\MongoDB\Query\Builder
     */
    protected function queryByStoreAndProductAndDates(
        $storeId = null,
        $productId = null,
        $dateFrom = null,
        $dateTo = null
    ) {
        $qb = $this->createQueryBuilder();
        if ($storeId) {
            $qb->field('store')->equals($storeId);
        }
        if ($productId) {
            $qb->field('originalProduct')->equals($productId);
        }
        if ($dateFrom) {
            $qb->field('date')->gte($dateFrom);
        }
        if ($dateTo) {
            $qb->field('date')->lte($dateTo);
        }
        $qb->sort('date', self::SORT_DESC);
        return $qb;
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor|StockMovementProduct[]
     */
    public function findByStoreAndProduct($storeId, $productId)
    {
        return $this->queryByStoreAndProductAndDates($storeId, $productId)->getQuery()->execute();
    }

    /**
     * @param string $storeId
     * @return Cursor|StockMovementProduct[]
     */
    public function findByStoreId($storeId)
    {
        return $this->queryByStoreAndProductAndDates($storeId)->getQuery()->execute();
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param string|\DateTime $dateFrom
     * @param string|\DateTime $dateTo
     * @return array
     */
    public function findParentIdsByStoreAndProductAndDates($storeId, $productId, $dateFrom, $dateTo)
    {
        $qb = $this->queryByStoreAndProductAndDates($storeId, $productId, $dateFrom, $dateTo);
        $qb->select('parent');
        $query = $qb->getQuery();
        $query->setHydrate(false);

        $parentIds = array();
        foreach ($query->execute() as $row) {
            $parentId = (string) $row['parent'];
            if (!isset($parentIds[$parentId])) {
                $parentIds[$parentId] = true;
            }
        }
        return array_keys($parentIds);
    }
}
