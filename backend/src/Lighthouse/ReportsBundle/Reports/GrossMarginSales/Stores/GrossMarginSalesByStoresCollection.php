<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\Stores;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReportCollection;

class GrossMarginSalesByStoresCollection extends GrossMarginSalesReportCollection
{
    /**
     * @param Store $store
     * @return GrossMarginSalesByStores
     */
    public function createByItem($store)
    {
        return new GrossMarginSalesByStores($store);
    }
}
