<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Exclude;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * @property StoreProduct   $storeProduct
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc", "storeProduct"="asc"})
 */
class GrossMarginSalesProductReport extends GrossMarginSalesReport
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
