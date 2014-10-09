<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts;

use Lighthouse\CoreBundle\Document\DocumentCollection;
use Lighthouse\CoreBundle\Document\Product\Product;

class GrossMarginSalesByProductsCollection extends DocumentCollection
{
    /**
     * @param Product $product
     * @return bool
     */
    public function containsProduct(Product $product)
    {
        return $this->containsKey($product->id);
    }

    /**
     * @param Product $product
     * @return GrossMarginSalesByProduct
     */
    public function getByProduct(Product $product)
    {
        if ($this->containsProduct($product)) {
            return $this->get($product->id);
        } else {
            return $this->createByProduct($product);
        }
    }

    /**
     * @param Product $product
     * @return GrossMarginSalesByProduct
     */
    public function createByProduct(Product $product)
    {
        $report = new GrossMarginSalesByProduct($product);
        $this->set($product->id, $report);
        return $report;
    }
}
