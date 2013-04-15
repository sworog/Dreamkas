<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Lighthouse\CoreBundle\Types\Money;

class ProductRepository extends DocumentRepository
{
    /**
     * @param Product $product
     * @param int $amountDiff
     * @param Money $lastPurchasePrice
     * @return Product
     */
    public function updateAmountAndLastPurchasePrice(Product $product, $amountDiff, Money $lastPurchasePrice = null)
    {
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->field('id')->equals($product->id)
            ->returnNew();
        if ($amountDiff <> 0) {
            $query->field('amount')->inc($amountDiff);
        }
        if ($lastPurchasePrice) {
            $query->field('lastPurchasePrice')->set($lastPurchasePrice->getCount());
        }
        $updatedProduct = $query->getQuery()->execute();
        return $updatedProduct;
    }

    /**
     * @param string $property
     * @param string $entry
     * @return LoggableCursor
     */
    public function searchEntry($property, $entry)
    {
        return $this->findBy(array($property => new \MongoRegex("/".preg_quote($entry, '/')."/i")));
    }
}
