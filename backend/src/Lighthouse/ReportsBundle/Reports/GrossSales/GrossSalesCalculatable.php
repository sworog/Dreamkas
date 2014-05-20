<?php

namespace Lighthouse\ReportsBundle\Reports\GrossSales;

use Doctrine\MongoDB\ArrayIterator;
use MongoId;

interface GrossSalesCalculatable
{
    /**
     * @param array $ids
     * @param string|\MongoId $storeId
     * @return ArrayIterator
     */
    public function calculateGrossSalesByIds(array $ids, $storeId);
}
