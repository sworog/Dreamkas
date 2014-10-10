<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesReport;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc", "subCategory"="asc"})
 *
 * @property SubCategory $subCategory
 */
class GrossMarginSalesCatalogGroupReport extends GrossMarginSalesReport
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory",
     *     simple=true
     * )
     * @var SubCategory
     */
    protected $subCategory;
}
