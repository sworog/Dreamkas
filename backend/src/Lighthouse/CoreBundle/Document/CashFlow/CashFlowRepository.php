<?php

namespace Lighthouse\CoreBundle\Document\CashFlow;

use Doctrine\ODM\MongoDB\MongoDBException;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use MongoId;

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

    /**
     * @param CashFlowable $reason
     * @return CashFlow|null
     */
    public function findOneByReason(CashFlowable $reason)
    {
        return $this->findOneByReasonTypeReasonId($reason->id, $reason->getCashFlowReasonType());
    }

    /**
     * @param string $reasonId
     * @param string $reasonType
     * @return null|CashFlow
     */
    public function findOneByReasonTypeReasonId($reasonId, $reasonType)
    {
        $criteria = array(
            'reason.$id' => new MongoId($reasonId),
            'reason.$ref' => $reasonType,
        );
        return $this->findOneBy($criteria);
    }
}
