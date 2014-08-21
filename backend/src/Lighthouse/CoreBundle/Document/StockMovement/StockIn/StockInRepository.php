<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\StockIn;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\LockMode;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository;

/**
 * @method StockIn find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method StockIn[]|Collection findAll()
 * @method StockIn createNew()
 */
class StockInRepository extends StockMovementRepository
{
    /**
     * @param $storeId
     * @param StockInFilter $filter
     * @return StockIn[]|Cursor
     */
    public function findByStore($storeId, StockInFilter $filter)
    {
        $criteria = array('store' => $storeId);
        $sort = array('date' => self::SORT_DESC);

        if ($filter->hasNumber()) {
            $criteria['number'] = $filter->getNumber();
        }

        return $this->findBy($criteria, $sort);
    }
}
