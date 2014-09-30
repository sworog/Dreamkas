<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts;

use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;

class GrossMarginSalesByProductsCollection extends DocumentCollection
{
    /**
     * @param StoreProduct $storeProduct
     * @return bool
     */
    public function containsStoreProduct(StoreProduct $storeProduct)
    {
        return $this->containsKey($storeProduct->id);
    }

    /**
     * @param StoreProduct $storeProduct
     * @return GrossMarginSalesByProduct
     */
    public function getByStoreProduct(StoreProduct $storeProduct)
    {
        if ($this->containsStoreProduct($storeProduct)) {
            return $this->get($storeProduct->id);
        } else {
            return $this->createByStoreProduct($storeProduct);
        }
    }

    /**
     * @param StoreProduct $storeProduct
     * @return GrossMarginSalesByProduct
     */
    public function createByStoreProduct(StoreProduct $storeProduct)
    {
        $report = new GrossMarginSalesByProduct($storeProduct);
        $this->set($storeProduct->id, $report);
        return $report;
    }
}
