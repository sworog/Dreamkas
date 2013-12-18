<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesByProducts;

use Lighthouse\CoreBundle\Document\AbstractCollection;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;

class GrossSalesByProductsCollection extends AbstractCollection
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
     * @param array $endDayHours
     * @return GrossSalesByProduct
     */
    public function createByStoreProduct(StoreProduct $storeProduct, array $endDayHours)
    {
        $report = new GrossSalesByProduct($storeProduct, $endDayHours);
        $this->set($storeProduct->id, $report);
        return $report;
    }

    /**
     * @param StoreProduct $storeProduct
     * @param array $endDayHours
     * @return GrossSalesByProduct
     */
    public function getByStoreProduct(StoreProduct $storeProduct, array $endDayHours)
    {
        if ($this->containsStoreProduct($storeProduct)) {
            return $this->get($storeProduct->id);
        } else {
            return $this->createByStoreProduct($storeProduct, $endDayHours);
        }
    }
}
