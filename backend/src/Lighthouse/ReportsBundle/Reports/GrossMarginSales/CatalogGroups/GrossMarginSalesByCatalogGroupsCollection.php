<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\CatalogGroups;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReport;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportCollection;

class GrossMarginSalesByCatalogGroupsCollection extends GrossMarginSalesReportCollection
{
    /**
     * @param SubCategory $catalogGroup
     * @return GrossMarginSalesReport
     */
    public function createByItem($catalogGroup)
    {
        return new GrossMarginSalesByCatalogGroups($catalogGroup);
    }
}
