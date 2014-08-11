<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\WriteOff;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use Doctrine\ODM\MongoDB\LockMode;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository;

/**
 * @method WriteOff find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method WriteOff createNew()
 */
class WriteOffRepository extends StockMovementRepository
{
    /**
     * @param $storeId
     * @param WriteOffFilter $filter
     * @return WriteOff[]|Cursor
     */
    public function findByStore($storeId, WriteOffFilter $filter)
    {
        $criteria = array('store' => $storeId);
        $sort = array('date' => self::SORT_DESC);

        if ($filter->hasNumber()) {
            $criteria['number'] = $filter->getNumber();
        }

        return $this->findBy($criteria, $sort);
    }

    /**
     * @param WriteOff $writeOff
     * @param int $itemsCountDiff
     * @param int $sumTotalDiff
     */
    public function updateTotals(WriteOff $writeOff, $itemsCountDiff, $sumTotalDiff)
    {
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->field('id')->equals($writeOff->id)
            ->returnNew();

        $query->field('itemsCount')->inc($itemsCountDiff);
        $query->field('sumTotal')->inc($sumTotalDiff);

        $query->getQuery()->execute();
    }
}
