<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\Mapping;

class ReceiptRepository extends StockMovementRepository
{
    /**
     * @param string $hash
     */
    public function rollbackByHash($hash)
    {
        $receipt = $this->findOneBy(array('hash' => $hash));
        $this->dm->remove($receipt);
        $this->dm->flush();
    }

    /**
     * @param array $criteria
     * @return Cursor|Receipt[]
     */
    public function findReceiptBy(array $criteria)
    {
        $queryBuilder = $this->dm->createQueryBuilder(Receipt::getClassName());
        foreach ($criteria as $field => $value) {
            $queryBuilder->field($field)->equals($value);
        }

        $result = $queryBuilder->getQuery()->execute();
        return $result;
    }
}
