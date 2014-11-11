<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\Products;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReport;

/**
 * @property Product        $product
 */
class GrossMarginSalesByProducts extends GrossMarginSalesReport
{
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

    /**
     * @return Product
     */
    public function getItem()
    {
        return $this->product;
    }
}
