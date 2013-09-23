<?php

namespace Lighthouse\CoreBundle\Document\WriteOff;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class WriteOffRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @return \Doctrine\ODM\MongoDB\Cursor
     */
    public function findByStore($storeId)
    {
        return $this->findBy(array('store' => $storeId));
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

        if ($itemsCountDiff <> 0) {
            $query->field('itemsCount')->inc($itemsCountDiff);
        }

        if ($sumTotalDiff <> 0) {
            $query->field('sumTotal')->inc($sumTotalDiff);
        }

        $query->getQuery()->execute();
    }
}
