<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ProductRepository extends DocumentRepository
{
    /**
     * @param Product $product
     * @param int $amountDiff
     * @return Product
     */
    public function updateAmount(Product $product, $amountDiff)
    {
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->field('id')->equals($product->id)
            ->field('amount')->inc($amountDiff)
            ->returnNew()
            ->getQuery();
        $updatedProduct = $query->execute();
        return $updatedProduct;
    }
}
