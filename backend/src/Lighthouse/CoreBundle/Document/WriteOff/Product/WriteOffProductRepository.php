<?php

namespace Lighthouse\CoreBundle\Document\WriteOff\Product;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\WriteOff\WriteOff;

class WriteOffProductRepository extends DocumentRepository
{
    /**
     * @param WriteOff $writeOff
     * @return WriteOffProductCollection
     */
    public function findAllByWriteOff(WriteOff $writeOff)
    {
        $cursor = $this->findBy(array('writeOff' => $writeOff->id));
        return new WriteOffProductCollection($cursor);
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @return Cursor
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
