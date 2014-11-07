<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\CatalogGroups;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesReport;

/**
 * @property SubCategory    $subCategory
 */
class GrossMarginSalesByCatalogGroups extends GrossMarginSalesReport
{
    /**
     * @var SubCategory
     */
    protected $subCategory;

    /**
     * @param SubCategory $subCategory
     */
    public function __construct(SubCategory $subCategory)
    {
        $this->subCategory = $subCategory;
    }

    /**
     * @return SubCategory
     */
    public function getItem()
    {
        return $this->subCategory;
    }
}
