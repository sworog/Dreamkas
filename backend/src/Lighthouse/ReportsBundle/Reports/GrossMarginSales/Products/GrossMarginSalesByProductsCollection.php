<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\Products;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReport;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportCollection;

class GrossMarginSalesByProductsCollection extends GrossMarginSalesReportCollection
{
    /**
     * @param Product $product
     * @return GrossMarginSalesReport
     */
    public function createByItem($product)
    {
        return new GrossMarginSalesByProducts($product);
    }
}
