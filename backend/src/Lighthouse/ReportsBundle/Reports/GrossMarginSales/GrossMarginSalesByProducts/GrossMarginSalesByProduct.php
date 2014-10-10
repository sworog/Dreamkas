<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;

/**
 * @property StoreProduct   $storeProduct
 * @property Product        $product
 */
class GrossMarginSalesByProduct extends GrossMarginSales
{
    /**
     * @var StoreProduct
     */
    protected $storeProduct;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
