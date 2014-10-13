<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Product;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property Product        $product
 * @property SubCategory    $subCategory
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc", "product"="asc"})
 */
class GrossMarginSalesProduct extends GrossMarginSales
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory",
     *     simple=true
     * )
     * @var SubCategory
     */
    protected $subCategory;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true
     * )
     *
     * @var Product
     */
    protected $product;
}
