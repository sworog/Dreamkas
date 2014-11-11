<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\Stores;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReport;

class GrossMarginSalesByStores extends GrossMarginSalesReport
{
    /**
     * @var Store
     */
    protected $store;

    /**
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return Store
     */
    public function getItem()
    {
        return $this->store;
    }
}
