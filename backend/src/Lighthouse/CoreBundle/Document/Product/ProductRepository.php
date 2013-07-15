<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use Doctrine\MongoDB\LoggableCursor;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Service\RoundService;
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

    public function findBySubCategory(SubCategory $subCategory)
    {
        $cursor = $this->findBy(array('subCategory' => $subCategory->id));
        return new ProductCollection($cursor);
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
        $roundedAveragePurchasePrice = RoundService::round($averagePurchasePrice);

        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            //->returnNew(true)
            ->field('id')->equals($productId)
            ->field('averagePurchasePrice')->set($roundedAveragePurchasePrice, true)
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
