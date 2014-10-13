<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Store;

use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Store\GrossMarginSalesStoreRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc", "store"="asc"})
 */
class GrossMarginSalesStore extends GrossMarginSales
{
}
