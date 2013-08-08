<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;

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
}
