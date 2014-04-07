<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\ParentableRepository;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use MongoId;
use MongoRegex;

class ProductRepository extends DocumentRepository implements ParentableRepository
{
    /**
     * @param string|array $properties
     * @param string $entry
     * @return Cursor
     */
    public function searchEntry($properties, $entry)
    {
        if (!is_array($properties)) {
            $properties = array($properties);
        }

        $or = array();
        foreach ($properties as $property) {
            $and = array();
            $words = explode(" ", $entry);
            array_walk($words, 'preg_quote');
            foreach ($words as $word) {
                $and[] = array($property => new MongoRegex("/" . $word . "/i"));
            }
            $or[] = array('$and' => $and);
        }

        return $this->findBy(array('$or' => $or), null, 100);
    }

    /**
     * @param SubCategory $subCategory
     * @return Cursor|Product[]
     */
    public function findBySubCategory(SubCategory $subCategory)
    {
        return $this->findBy(array('subCategory' => $subCategory->id));
    }

    /**
     * @param string $parentId
     * @return MongoId[]
     */
    public function findIdsByParent($parentId)
    {
        $qb = $this->createQueryBuilder()
            ->hydrate(false)
            ->select('_id')
            ->field('subCategory')->equals($parentId);
        $result = $qb->getQuery()->execute();
        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row['_id'];
        }
        return $ids;
    }

    /**
     * @param string $sku
     * @return Product
     */
    public function findOneBySku($sku)
    {
        return $this->findOneBy(array('sku' => $sku));
    }

    /**
     * @param string $parentId
     * @return int
     */
    public function countByParent($parentId)
    {
        $query = $this
            ->createQueryBuilder()
            ->field('subCategory')->equals($parentId)
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
            $roundedMarkup = Decimal::createFromNumeric($markup, 2)->toNumber();
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
