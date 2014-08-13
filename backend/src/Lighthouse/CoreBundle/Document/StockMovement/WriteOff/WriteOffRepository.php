<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\WriteOff;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\LockMode;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository;

/**
 * @method WriteOff find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method WriteOff[]|Collection findAll()
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
}
