<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ShopProductRepository extends DocumentRepository
{
    /**
     * @param Product $product
     * @param int $amountDiff
     */
    public function updateAmount(Product $product, $amountDiff)
    {
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->field('product')->equals($product->id)
            ->field('amount')->inc($amountDiff)
            ->returnNew() // is needed for ShopProduct to be updated in IdentityMap
            ->upsert()
            ->getQuery();
        $shopProduct = $query->execute();
        return $shopProduct;
    }
}