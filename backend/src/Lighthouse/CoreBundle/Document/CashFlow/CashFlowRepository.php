<?php

namespace Lighthouse\CoreBundle\Document\CashFlow;

use Doctrine\ODM\MongoDB\MongoDBException;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class CashFlowRepository extends DocumentRepository
{
    /**
     * @param CashFlowFilter $filter
     * @return mixed
     * @throws MongoDBException
     */
    public function findCashFlowsByFilter(CashFlowFilter $filter)
    {
        $qb = $this->createQueryBuilder();
        if (isset($filter->dateFrom)) {
            $qb->field('date')->gte($filter->dateFrom);
        }
        if (isset($filter->dateTo)) {
            $qb->field('date')->lte($filter->dateTo);
        }

        $qb->sort('date', self::SORT_DESC);

        return $qb->getQuery()->execute();
    }
}
