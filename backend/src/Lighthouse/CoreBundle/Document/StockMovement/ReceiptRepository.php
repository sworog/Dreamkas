<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\Mapping;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Exception\RuntimeException;

class ReceiptRepository extends StockMovementRepository
{
    /**
     * @param string $type
     * @return Returne|Sale
     */
    public function createNewByType($type)
    {
        switch ($type) {
            case Sale::TYPE:
                $receipt = new Sale();
                break;
            case Returne::TYPE:
                $receipt = new Returne();
                break;
            default:
                throw new RuntimeException(sprintf("Invalid receipt type given: '%s'", $type));
        }

        $receipt->sumTotal = $this->numericFactory->createMoney();
        return $receipt;
    }

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
