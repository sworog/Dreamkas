<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(
 *      repositoryClass
 *          = "Lighthouse\ReportsBundle\Document\GrossMarginSales\CatalogGroup\GrossMarginSalesCatalogGroupRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc", "subCategory"="asc"})
 *
 * @property SubCategory $subCategory
 */
class GrossMarginSalesCatalogGroup extends GrossMarginSales
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
