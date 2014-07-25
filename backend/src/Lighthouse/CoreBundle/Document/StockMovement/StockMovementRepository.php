<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class StockMovementRepository extends DocumentRepository
{
    /**
     * @return Cursor|StockMovement[]
     */
    public function findAll()
    {
        return $this->findBy(array(), array('date' => self::SORT_DESC));
    }
}
