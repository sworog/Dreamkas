<?php

namespace Lighthouse\CoreBundle\Document\Receipt;

use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class ReceiptRepository extends DocumentRepository
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
     * @return Cursor
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
