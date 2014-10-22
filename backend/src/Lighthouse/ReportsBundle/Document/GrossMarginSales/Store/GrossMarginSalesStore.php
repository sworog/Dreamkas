<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Store;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Store\GrossMarginSalesStoreRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc"})
 */
class GrossMarginSalesStore extends GrossMarginSales
{
    /**
     * @return Store
     */
    public function getItem()
    {
        return $this->store;
    }
}
