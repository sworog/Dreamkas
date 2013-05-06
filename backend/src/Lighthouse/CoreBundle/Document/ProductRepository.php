<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Lighthouse\CoreBundle\Types\Money;

class ProductRepository extends DocumentRepository
{
    /**
     * @param string $property
     * @param string $entry
     * @return LoggableCursor
     */
    public function searchEntry($property, $entry)
    {
        return $this->findBy(array($property => new \MongoRegex("/".preg_quote($entry, '/')."/i")));
    }

    /**
     * @param Product $product
     * @param Money $purchasePrice
     */
    public function updateLastPurchasePrice(Product $product, Money $purchasePrice = null)
    {
        $lastPurchasePrice = (null !== $purchasePrice) ? $purchasePrice->getCount() : null;
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->returnNew(true)
            ->field('id')->equals($product->id)
            ->field('lastPurchasePrice')->set($lastPurchasePrice, true);

        $query->getQuery()->execute();
    }

    /**
     * @param string $productId
     * @param int $averagePurchasePrice
     */
    public function updateAveragePurchasePrice($productId, $averagePurchasePrice)
    {
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            //->returnNew(true)
            ->field('id')->equals($productId)
            ->field('averagePurchasePrice')->set($averagePurchasePrice, true)
            ->field('averagePurchasePriceNotCalculate')->unsetField();

        $query->getQuery()->execute();
    }

    public function setAllAveragePurchasePriceToNotCalculate()
    {
        $query = $this->createQueryBuilder()
            ->update()
            ->multiple(true)
            ->field('averagePurchasePrice')->notEqual(null)
            ->field('averagePurchasePriceNotCalculate')->set(true, true);

        $query->getQuery()->execute();
    }

    public function resetAveragePurchasePriceNotCalculate()
    {
        $query = $this->createQueryBuilder()
            ->update()
            ->multiple(true)
            ->field('averagePurchasePriceNotCalculate')->equals(true)
            ->field('averagePurchasePrice')->set(null, true)
            ->field('averagePurchasePriceNotCalculate')->unsetField();

        $query->getQuery()->execute();
    }
}
