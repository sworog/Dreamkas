<?php

namespace Lighthouse\CoreBundle\Document\WriteOff\Product;

use Doctrine\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;

class WriteOffProductRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor|WriteOff[]
     */
    public function findByStoreAndProduct($storeId, $productId)
    {
        $criteria = array(
            'store' => $storeId,
            'originalProduct' => $productId,
        );
        $sort = array(
            'createdDate' => self::SORT_DESC,
        );
        return $this->findBy($criteria, $sort);
    }
}
