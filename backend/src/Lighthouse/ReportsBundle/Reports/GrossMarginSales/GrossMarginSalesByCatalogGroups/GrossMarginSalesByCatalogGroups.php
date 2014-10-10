<?php

namespace Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByCatalogGroups;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\GrossMarginSalesByProducts\GrossMarginSales;

/**
 * @property SubCategory    $subCategory
 */
class GrossMarginSalesByCatalogGroups extends GrossMarginSales
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
}
