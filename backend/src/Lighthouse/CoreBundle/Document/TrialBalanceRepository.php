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
    public function findOneByProduct(Product $product, InvoiceProduct $invoiceProduct = null)
    {
        $criteria = array('product' => $product->id);
        if (null !== $invoiceProduct) {
            $criteria['reason.$id'] = array('$ne' => new \MongoId($invoiceProduct->id));
            //$criteria['reason.$ref'] = array('$ne' => 'InvoiceProduct');
        }
        // Ugly hack to force document refresh
        $hints = array(Query::HINT_REFRESH => true);
        $sort = array(
            'createdDate' => -1,
            '_id' => -1,
        );
        return $this->uow->getDocumentPersister($this->documentName)->load($criteria, null, $hints, 0, $sort);
    }

    /**
     * @param Product $product
     * @return TrialBalance
     */
    public function findOneReasonInvoiceProductByProduct(Product $product)
    {
        $criteria = array('product' => $product->id);
        $criteria['reason.$ref'] = 'InvoiceProduct';
        // Ugly hack to force document refresh
        $hints = array(Query::HINT_REFRESH => true);
        $sort = array(
            'createdDate' => -1,
            '_id' => -1,
        );
        return $this->uow->getDocumentPersister($this->documentName)->load($criteria, null, $hints, 0, $sort);
    }

    /**
     * @return array
     */
    public function calculateAveragePurchasePrice()
    {
        $dateStart = new \MongoDate(strtotime("-30 day 00:00"));
        $dateEnd = new \MongoDate(strtotime("00:00"));

        $query = $this
            ->createQueryBuilder()
            ->field('createdDate')->gt($dateStart)
            ->field('createdDate')->lt($dateEnd)
            ->map(
                new \MongoCode(
                    "function() {
                        emit(
                            this.product,
                            {
                                totalPrice: this.totalPrice,
                                quantity: this.quantity
                            }
                        )
                    }"
                )
            )
            ->reduce(
                new \MongoCode(
                    "function(productId, obj) {
                        var reducedObj = {totalPrice: 0, quantity: 0}
                        for (var item in obj) {
                            reducedObj.totalPrice += obj[item].totalPrice;
                            reducedObj.quantity += obj[item].quantity;
                        }
                        return reducedObj;
                    }"
                )
            )
            ->finalize(
                new \MongoCode(
                    "function(productId, obj) {
                        if (obj.quantity > 0) {
                            obj.averagePrice = obj.totalPrice / obj.quantity;
                        } else {
                            obj.averagePrice = null;
                        }
                        return obj;
                    }"
                )
            )
            ->out(array('inline' => true));

        return $query->getQuery()->execute();
    }
}