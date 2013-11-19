<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use Doctrine\MongoDB\LoggableCursor;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Service\RoundService;
use Lighthouse\CoreBundle\Types\Numeric\Money;

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
     * @param SubCategory $subCategory
     * @return ProductCollection
     */
    public function findBySubCategory(SubCategory $subCategory)
    {
        $cursor = $this->findBy(array('subCategory' => $subCategory->id));
        return new ProductCollection($cursor);
    }

    /**
     * @param string $subCategoryId
     * @return int
     */
    public function countBySubCategory($subCategoryId)
    {
        $query = $this
            ->createQueryBuilder()
            ->field('subCategory')->equals($subCategoryId)
            ->count()
            ->getQuery();
        $count = $query->execute();
        return $count;
    }

    /**
     * @param Product $product
     */
    public function updateRetails(Product $product)
    {
        switch ($product->retailPricePreference) {
            case Product::RETAIL_PRICE_PREFERENCE_PRICE:
                $product->retailMarkupMin = $this->calcMarkup($product->retailPriceMin, $product->purchasePrice);
                $product->retailMarkupMax = $this->calcMarkup($product->retailPriceMax, $product->purchasePrice);
                break;
            case Product::RETAIL_PRICE_PREFERENCE_MARKUP:
            default:
                $product->retailPriceMin = $this->calcRetailPrice($product->retailMarkupMin, $product->purchasePrice);
                $product->retailPriceMax = $this->calcRetailPrice($product->retailMarkupMax, $product->purchasePrice);
                $product->retailPricePreference = Product::RETAIL_PRICE_PREFERENCE_MARKUP;
                break;
        }
    }

    /**
     * @param Money $retailPrice
     * @param Money $purchasePrice
     * @return float|null
     */
    protected function calcMarkup(Money $retailPrice = null, Money $purchasePrice = null)
    {
        $roundedMarkup = null;
        if (null !== $retailPrice && !$retailPrice->isNull() && null !== $purchasePrice && !$purchasePrice->isNull()) {
            $markup = (($retailPrice->getCount() / $purchasePrice->getCount()) * 100) - 100;
            $roundedMarkup = RoundService::round($markup, 2);
        }
        return $roundedMarkup;
    }

    /**
     * @param float $retailMarkup
     * @param Money $purchasePrice
     * @return Money
     */
    protected function calcRetailPrice($retailMarkup, Money $purchasePrice = null)
    {
        if (null !== $retailMarkup && '' !== $retailMarkup && null !== $purchasePrice && !$purchasePrice->isNull()) {
            $percent = 1 + ($retailMarkup / 100);
            $retailPrice = $purchasePrice->mul($percent);
        } else {
            $retailPrice = new Money();
        }
        return $retailPrice;
    }
}
