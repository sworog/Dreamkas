<?php

namespace Lighthouse\CoreBundle\Document\Order;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class OrderRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @return Cursor
     */
    public function findAllByStoreId($storeId)
    {
        return $this->findBy(array('store' => $storeId), array('createdDate' => self::SORT_DESC));
    }
}
