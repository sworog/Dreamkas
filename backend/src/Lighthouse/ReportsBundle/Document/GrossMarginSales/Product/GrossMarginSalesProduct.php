<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Product;

use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @property StoreProduct   $storeProduct
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc", "storeProduct"="asc"})
 */
class GrossMarginSalesProduct extends GrossMarginSales
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Store\StoreProduct",
     *     simple=true
     * )
     *
     * @var StoreProduct
     */
    protected $storeProduct;
}
