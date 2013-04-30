<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Query;

class TrialBalanceRepository extends DocumentRepository
{
    /**
     * @param InvoiceProduct $reason
     * @return TrialBalance
     */
    public function findOneByReason($reason)
    {
        if ($reason instanceof InvoiceProduct) {
            $criteria = array(
                'reason.$id' => new \MongoId($reason->id),
                'reason.$ref' => 'InvoiceProduct',
            );
        }
        if (isset($criteria)) {
            $result = $this->findOneBy($criteria);
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * @param Product $product
     * @return TrialBalance|null
     */
    public function findOneByProduct(Product $product)
    {
        $criteria = array('product' => $product->id);
        // Ugly hack to force document refresh
        $hints = array(Query::HINT_REFRESH => true);
        return $this->uow->getDocumentPersister($this->documentName)->load($criteria, null, $hints);
    }
}