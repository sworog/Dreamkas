<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductCollection;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Service\RoundService;
use Lighthouse\CoreBundle\Types\Money;

class StoreProductRepository extends DocumentRepository
{
    /**
     * @param string $storeId
     * @param string $productId
     * @return StoreProduct
     */
    public function findByStoreIdProductId($storeId, $productId)
    {
        return $this->findOneBy(array('store' => $storeId, 'product' => $productId));
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return StoreProduct
     */
    public function createByStoreProduct(Store $store, Product $product)
    {
        $storeProduct = new StoreProduct();
        $storeProduct->store = $store;
        $storeProduct->product = $product;
        $storeProduct->subCategory = $product->subCategory;

        return $storeProduct;
    }

    /**
     * @param Store $store
     * @param Product $product
     */
    public function findOrCreateByStoreProduct(Store $store, Product $product)
    {
        $storeProduct = $this->findByStoreIdProductId($store->id, $product->id);
        if (null === $storeProduct) {
            $storeProduct = $this->createByStoreProduct($store, $product);
        }
        return $storeProduct;
    }

    /**
     * @param SubCategory $subCategory
     * @return StoreProductCollection
     */
    public function findBySubCategory(SubCategory $subCategory)
    {
        $cursor = $this->findBy(array('subCategory' => $subCategory->id));
        return new StoreProductCollection($cursor);
    }

    /**
     * @param StoreProduct $storeProduct
     */
    public function updateRetails(StoreProduct $storeProduct)
    {
        switch ($storeProduct->retailPricePreference) {
            case Product::RETAIL_PRICE_PREFERENCE_PRICE:
                $storeProduct->retailMarkup = $this->calcMarkup(
                    $storeProduct->retailPrice,
                    $storeProduct->product->purchasePrice
                );
                break;
            case Product::RETAIL_PRICE_PREFERENCE_MARKUP:
            default:
                $storeProduct->retailPrice = $this->calcRetailPrice(
                    $storeProduct->retailMarkup,
                    $storeProduct->product->purchasePrice
                );
                $storeProduct->retailPricePreference = Product::RETAIL_PRICE_PREFERENCE_MARKUP;
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
        if (null !== $retailPrice && !$retailPrice->isNull() && null !== $purchasePrice) {
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
        $retailPrice = new Money();
        if (null !== $retailMarkup && '' !== $retailMarkup && null !== $purchasePrice) {
            $percent = 1 + ($retailMarkup / 100);
            $retailPrice->setCountByQuantity($purchasePrice, $percent, true);
        }
        return $retailPrice;
    }
}
