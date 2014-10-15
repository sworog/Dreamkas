<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Network;

use Lighthouse\ReportsBundle\Document\GrossMarginSales\GrossMarginSales;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Network\GrossMarginSalesNetworkRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc"})
 */
class GrossMarginSalesNetwork extends GrossMarginSales
{
    /**
     * @return object
     */
    public function getItem()
    {
        return null;
    }
}
